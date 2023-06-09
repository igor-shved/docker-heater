<template>
    <modal_window
        :roomProps="curRoom"
        :key="'modalAllSetting' + this.curRoom.id"
        :classArrayProps="classArray"
        :zIndexProps="curIndexProps + 1"

    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModal" class="modal__close">X</a>
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
            <button @click.prevent="saveSetting" class="modal__footer_button">OK</button>
        </template>

    </modal_window>

    <modal_select_temp v-if="isOpenModalRightNowTemp"
                       :roomProps="curRoom"
                       :tempProps="rightNowTemp"
                       :nameValueProps="'rightNowTemp'"
                       :key="'ModalRightNowTemp' + this.curRoom.id"
                       :classArrayProps="classArray"
                       :zIndexProps="curIndexProps + 2"
                       :nameSelectTempProps="'selectTempMain'"
    >
    </modal_select_temp>
    <modal_select_temp v-if="isOpenModalStandByTemp"
                       :roomProps="curRoom"
                       :tempProps="standByTemp"
                       :nameValueProps="'standByTemp'"
                       :key="'ModalStandByTemp' + this.curRoom.id"
                       :classArrayProps="classArray"
                       :zIndexProps="curIndexProps + 2"
                       :nameSelectTempProps="'selectTempMain'"
    >
    </modal_select_temp>
    <modal_setting_relay_temp v-if="isOpenModalSettingRelayTemp"
                       :objSettingRelayTempProps="objSettingRelayTemp"
                       :key="'ModalSettingRelayTemp' + this.curRoom.id"
                       :classArrayProps="classArray"
                       :zIndexProps="curIndexProps + 2"
    >
    </modal_setting_relay_temp>

</template>

<script>
import settings_list from "../mode/SettingsList.vue";
import modal_window from "./ModalWindow.vue";
import mode_list from "../mode/ModeList.vue";
import modal_select_temp from "./ModalSelectTemp.vue";
import modal_setting_relay_temp from "./ModalSettingRelayTemp.vue"
import {mapActions, mapState} from "vuex";

export default {
    name: "modal_all_setting",
    components: {mode_list, modal_window, settings_list, modal_select_temp, modal_setting_relay_temp},
    props: ['roomProps', 'curIndexProps'],
    data() {
        return {
            isOpen: false,
            curRoom: this.roomProps,
            currentMode: this.roomProps.currentMode,
            rightNowTemp: this.roomProps.rightNowTemp,
            roomsPOutputs: this.roomProps.roomsPOutputs,
            roomsTsensors: this.roomProps.roomsTsensors,
            roomName: this.roomProps.roomName,
            tempName: this.roomProps.tempName,
            relayName: this.roomProps.relayName,
            standByTemp: this.roomProps.standByTemp,
            scheduleSetting: this.roomProps.scheduleSetting,
            isOpenModalRightNowTemp: false,
            isOpenModalStandByTemp: false,
            isOpenModalSettingRelayTemp: false,
            arrayModes: [],
            classArray: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
            },
            objSettingRelayTemp: {},
        }
    },
    beforeMount() {
        this.COPY_SETTING_ROOM_TO_ARRAY(this.curRoom);
        this.$eventBus.$on('modal_select_mode', this.selectMode);
        this.$eventBus.$on('modal_select_temp', this.selectTemp);
        this.$eventBus.$on('open_modal_setting_relay_temp', this.openSettingRelayTemp);
        this.$eventBus.$on('open_select_stand_by_temp', this.openSelectStandByTemp);
        this.$eventBus.$on('modal_select_relay_temp_name', this.selectRelayTempName);
        this.$eventBus.$on('modal_select_schedule_setting', this.selectScheduleSetting);
    },
    beforeUnmount() {
        this.$eventBus.$off('modal_select_mode', this.selectMode);
        this.$eventBus.$off('modal_select_temp', this.selectTemp);
        this.$eventBus.$off('open_modal_setting_relay_temp', this.openSettingRelayTemp);
        this.$eventBus.$off('open_select_stand_by_temp', this.openSelectStandByTemp);
        this.$eventBus.$off('modal_select_relay_temp_name', this.selectRelayTempName);
        this.$eventBus.$off('modal_select_schedule_setting', this.selectScheduleSetting);
    },
    methods: {
        ...mapActions(['COPY_SETTING_ROOM_TO_ARRAY']),
        closeModal() {
            this.$eventBus.$emit('modal_all_setting', 'close', this.curRoom);
        },
        selectMode(selMode) {
            if (selMode === 1) {
                this.selectRightNowTemp('open', this.rightNowTemp);
            }
            this.currentMode = selMode;
        },
        openSelectStandByTemp(){
            this.selectStandByTemp('open', this.standByTemp);
        },
        selectTemp(objArg) {
            if (objArg.nameValue === "rightNowTemp") {
                this.selectRightNowTemp(objArg.eventName, objArg.temp);
            } else if (objArg.nameValue === "standByTemp") {
                this.selectStandByTemp(objArg.eventName, objArg.temp);
            }
        },
        selectRightNowTemp(eventName, selTemp) {
            if (eventName === 'open') {
                this.isOpenModalRightNowTemp = true;
            } else if (eventName === 'close') {
                this.isOpenModalRightNowTemp = false;
                this.rightNowTemp = selTemp;
            } else if (eventName === 'save') {
                this.isOpenModalRightNowTemp = false;
                this.rightNowTemp = selTemp;
            }
        },
        selectStandByTemp(eventName, selTemp) {
            if (eventName === 'open') {
                this.isOpenModalStandByTemp = true;
            } else if (eventName === 'close') {
                this.isOpenModalStandByTemp = false;
                this.standByTemp = selTemp;
            } else if (eventName === 'save') {
                this.isOpenModalStandByTemp = false;
                this.standByTemp = selTemp
            }
        },
        selectRelayTempName(objArg) {
            if (objArg.eventName === 'close') {
                this.isOpenModalSettingRelayTemp = false;
            } else if (objArg.eventName === 'save') {
                this.isOpenModalSettingRelayTemp = false;
                this.roomsPOutputs = objArg.roomsPOutputs;
                this.roomsTsensors = objArg.roomsTsensors;
                this.roomName = objArg.roomName;
                this.tempName = objArg.tempName;
                this.relayName = objArg.relayName;
            }
        },
        openSettingRelayTemp(){
            this.objSettingRelayTemp = {
                roomId: this.curRoom.id,
                roomsPOutputs: this.roomsPOutputs,
                roomsTsensors: this.roomsTsensors,
                roomName: this.roomName,
                tempName: this.tempName,
                relayName: this.relayName,
            }
            this.isOpenModalSettingRelayTemp = true;
        },
        selectScheduleSetting(arraySchedule) {

        },
        saveSetting() {
        },
        addMissingSetting() {

        },
    },

    computed: {
        ...mapState(
            {copySettingRoom: 'copySettingRoom'}
        ),
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
