<template>
    <modal_window v-if="isOpenModalMode"
                  :roomProps="curRoom"
                  :key="'modalAllSetting'"
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
            <button @click="saveSelectMode" class="modal__footer_button">OK</button>
        </template>

    </modal_window>
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
import {mapState, mapActions} from "vuex";

export default {
    name: "heater_list",
    components: {
        room_block, modal_window, mode_list, settings_list, mode_block
    },
    data() {
        return {
            arrayModes: [],
            isOpenModalMode: false,
            curRoom: undefined,
            selectMode: undefined,
            classMode: '',
            classArrayModal: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
                'modal__modal_mode': true,
            }
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
    },
    beforeDestroy() {
        this.$eventBus.$off('open_modal_mode', this.openModalMode);
        this.$eventBus.$off('close_modal_mode', this.closeModalMode);
        this.$eventBus.$off('select_mode_set', this.selectModeSet);
    },
    methods: {
        ...mapActions(['LOAD_ROOMS_DATA', 'SET_NEW_SETTING_ARRAY']),
        openModalMode(selRoom) {
            this.curRoom = selRoom;
            this.arrayModes = this.$getArrayModesFromRoom(this.curRoom.id);
            this.isOpenModalMode = true;
            document.body.classList.add('body__Overflow_y_hidden');
        },
        closeModalMode() {
            this.isOpenModalMode = false;
            document.body.classList.remove('body__Overflow_y_hidden');
        },
        selectModeSet(selMode) {
            this.selectMode = selMode;
        },
        saveSelectMode() {
            if (this.selectMode != undefined) {
                this.SET_NEW_SETTING_ARRAY(
                    {
                        idRoom: this.curRoom.id,
                        name: 'selectMode',
                        value: this.selectMode
                    }
                );
                this.curRoom.currentMode = this.selectMode;
            }
            this.closeModalMode();
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
