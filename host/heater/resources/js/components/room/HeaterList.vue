<template>
    <modal_window v-if="isOpenModalMode"
                  :roomProps="curRoom"
                  :key="'modalAllSetting' + this.curRoom.id"
                  :classProps="classArrayModal"

    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModalMode" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки
                </div>
                <div v-else>
                    Настройки комнаты № {{ curRoomId }}
                </div>
            </div>
            <div class="modal__title">
                {{ curRoomName }}
            </div>
            <div class="modal__title">
                Режим: {{ selectModeText }}
            </div>
        </template>

        <template #content>
            <mode_list
                :roomProps="curRoom"
            />
            <settings_list
                :roomProps="curRoom"
            />
        </template>

        <template #footer>
            <button @click.prevent="saveModalMode" class="modal__footer_button">OK</button>
        </template>

    </modal_window>
    <modal_child v-if="isOpenModalRightNowTemp"
                 :key="'modalTemp'+String(curRoom.id)"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeRightNowTemp" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ curRoom.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ curRoom.roomName }}
            </div>
            <div class="modal__title">
                Настройка: ручной нагрев
            </div>
        </template>
        <template #content>
            <select_temperature
                :key="'selectRightNowTemp'+String(curRoom.id)"
                :tempSelectProps="rightNowTemp"
                :nameSelectModeProps="'changeRightNowTemp'"
            >
            </select_temperature>
        </template>
        <template #footer>
            <button @click.prevent="saveRightNowTemp" class="modal__footer_button">OK</button>
        </template>
    </modal_child>

    <template v-if="isLoadDataRooms">
        <room_block v-for="room in roomsData"
                    :roomProps="room"
                    :key="'room'+ String(room.id)"
        />
    </template>
    <div v-else class="loading_status">
        <span>Loading data</span>
    </div>
</template>

<script>
import room_block from "./RoomBlock.vue";
import modal_window from "../modal/ModalWindow.vue";
import mode_list from "../mode/ModeList.vue";
import settings_list from "../mode/SettingsList.vue";
import mode_block from "../mode/ModeBlock.vue";
import select_temperature from "../mode/SelectTemperature.vue";
import modal_child from "../modal/ModalChild.vue";
import {mapState, mapActions} from "vuex";
import axios from "axios";

export default {
    name: "heater_list",
    components: {modal_child, room_block, modal_window, mode_list, settings_list, mode_block, select_temperature},
    data() {
        return {
            arrayModes: [],
            isOpenModalMode: false,
            curRoom: undefined,
            idSelRoom: undefined,
            selectMode: undefined,
            rightNowTemp: 0,
            beforeChangeRightNowTemp: 0,
            beforeRightNowTemp: 0,
            isOpenModalRightNowTemp: false,
            arrScheduleBeforeChange: [],
            classMode: '',
            arrayNameSetting: ['currentMode', 'rightNowTemp', 'roomsPOutputs', 'roomsTsensors', 'standByTemp', 'scheduleSetting'],
            classArrayModal: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
                'modal__modal_mode': true,
            },
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_period_mode': true,
                'modal__shadow_background': true,
            },
        }
    },
    created() {
        // this.$eventBus.$on('data_load', this.onDataLoad);
        // setTimeout(() => {
        //     this.LOAD_ROOMS_DATA('/api/get_rooms_data')
        // }, 30000);
        this.LOAD_ROOMS_DATA('/api/get_rooms_data');
        this.$eventBus.$on('open_modal_mode', this.openModalMode);
        this.$eventBus.$on('close_modal_mode', this.closeModalMode);
        this.$eventBus.$on('select_mode_set', this.selectModeSet);
        this.$eventBus.$on('select_right_now_temp', this.selectRightNowTemp);
    },
    beforeDestroy() {
        this.$eventBus.$off('open_modal_mode', this.openModalMode);
        this.$eventBus.$off('close_modal_mode', this.closeModalMode);
        this.$eventBus.$off('select_mode_set', this.selectModeSet);
        this.$eventBus.$off('select_right_now_temp', this.selectRightNowTemp);
    },
    methods: {
        ...mapActions(['LOAD_ROOMS_DATA', 'SET_NEW_SETTING_ARRAY']),
        openModalMode(selRoom) {
            this.curRoom = selRoom;
            this.selectMode = this.curRoom.currentMode;
            this.$eventBus.$emit('select_mode_click', this.selectMode);
            this.rightNowTemp = this.curRoom.rightNowTemp;
            this.beforeRightNowTemp = this.rightNowTemp;
            if (this.curRoom.id !== this.idSelRoom) {
                this.arrScheduleBeforeChange = selRoom.scheduleArrRoom.map(item => ({...item}));
            }
            this.arrayModes = this.$getArrayModesFromRoom(this.curRoom.id);
            this.isOpenModalMode = true;
            document.body.classList.add('body__Overflow_y_hidden');
            this.idSelRoom = selRoom.id;
        },
        closeModalMode() {
            if (this.curRoom != undefined && !this.$isEqualArrays(this.arrScheduleBeforeChange, this.curRoom.scheduleArrRoom)) {
                this.curRoom.scheduleArrRoom = this.arrScheduleBeforeChange.map(item => ({...item}));
            }
            if (this.selectMode !== this.curRoom.currentMode) {
                this.selectMode = this.curRoom.currentMode;
            }
            this.isOpenModalMode = false;
            document.body.classList.remove('body__Overflow_y_hidden');
        },
        saveModalMode() {
            let needSave = false;
            if (this.curRoom != undefined && !this.$isEqualArrays(this.arrScheduleBeforeChange, this.curRoom.scheduleArrRoom)) {
                // console.log('arrScheduleBeforeChange before save select mode');
                // console.log(this.arrScheduleBeforeChange);
                this.arrScheduleBeforeChange = this.curRoom.scheduleArrRoom.map(item => ({...item}));
                this.$setNewSetting(this.curRoom.id, 'scheduleSetting', this.curRoom.scheduleArrRoom);
            }

            if (this.selectMode !== this.curRoom.currentMode) {
                this.$setNewSetting(this.curRoom.id, 'currentMode', this.selectMode);
                this.curRoom.currentMode = this.selectMode;
            }
            // console.log(this.curRoom);
            // console.log(this.curRoom.scheduleArrRoom);
            // console.log(this.arrayNewSetting);
            this.addMissingSetting();
            // axios.post('/api/post_files_data', {data: this.arrayNewSetting})
            //     .then(response => {
            //         console.log('save setting to room ' + this.arrayNewSetting);
            //     })
            //     .catch(err => {
            //         console.log('error /api/save_setting_to_files', err.response.data);
            //     })
            //console.log(this.arrayNewSetting);
            this.closeModalMode();
        },
        addMissingSetting() {

        },
        closeRightNowTemp() {
            this.rightNowTemp = this.beforeChangeRightNowTemp;
            this.isOpenModalRightNowTemp = false;
        },
        saveRightNowTemp() {
            this.isOpenModalRightNowTemp = false;
        },
        selectModeSet(selMode) {
            //console.log('selMode = ' + selMode);
            if (selMode === 1) {
                this.beforeChangeRightNowTemp = this.rightNowTemp;
                this.isOpenModalRightNowTemp = true;
            }
            this.selectMode = selMode;
        },
        selectRightNowTemp(selTemp) {
            this.rightNowTemp = selTemp;
        },
    },

    computed: {
        ...mapState({
            roomsData: 'roomsData',
            isLoadDataRooms: 'isLoadDataRooms',
            arrayNewSetting: 'arrayNewSetting',
        }),
        itMainBlock() {
            return this.curRoom.id === 0 ? true : false;
        },
        curRoomId() {
            return this.curRoom.id;
        },
        curRoomName() {
            return this.curRoom.roomName;
        },
        selectModeText() {
            let filterItem = this.arrayModes.filter(item =>
                item.id === this.curRoom.currentMode
            );
            let textMode = '';
            if (filterItem.length === 1) {
                textMode = filterItem[0]['textMode'];
            }
            return textMode;
        },
    },
}
</script>

<style scoped>
</style>
