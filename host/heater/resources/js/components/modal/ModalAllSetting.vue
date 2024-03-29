<template>
    <modal_window
        :roomProps="curRoom"
        :key="'modalAllSetting' + this.curRoom.id"
        :classArrayProps="classArray"
        :zIndexProps="zIndexProps + 1"
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
                       :zIndexProps="zIndexProps + 2"
                       :nameSelectTempProps="'selectTempMain'"
    >
    </modal_select_temp>
    <modal_select_temp v-if="isOpenModalStandByTemp"
                       :roomProps="curRoom"
                       :tempProps="standByTemp"
                       :nameValueProps="'standByTemp'"
                       :key="'ModalStandByTemp' + this.curRoom.id"
                       :classArrayProps="classArray"
                       :zIndexProps="zIndexProps + 2"
                       :nameSelectTempProps="'selectTempMain'"
    >
    </modal_select_temp>
    <modal_setting_relay_temp v-if="isOpenModalSettingRelayTemp"
                              :objSettingProps="objSettingRelayTemp"
                              :key="'ModalSettingRelayTemp' + this.curRoom.id"
                              :classArrayProps="classArray"
                              :zIndexProps="zIndexProps + 2"
    >
    </modal_setting_relay_temp>

    <modal_schedule v-if="isOpenModalSchedule"
                    :objSettingProps="objSettingSchedule"
                    :key="'ModalSchedule' + this.curRoom.id"
                    :classArrayProps="classArray"
                    :zIndexProps="zIndexProps + 2"
                    @close_modal_schedule="closeModalSchedule"
    >
    </modal_schedule>

</template>

<script>
import settings_list from "../mode/SettingsList.vue";
import modal_window from "./ModalWindow.vue";
import mode_list from "../mode/ModeList.vue";
import modal_select_temp from "./ModalSelectTemp.vue";
import modal_setting_relay_temp from "./ModalSettingRelayTemp.vue"
import modal_schedule from "./ModalSchedule.vue"
import {mapActions, mapState} from "vuex";
import axios from "axios";

export default {
    name: "modal_all_setting",
    components: {mode_list, modal_window, settings_list, modal_select_temp, modal_setting_relay_temp, modal_schedule},
    props: ['roomProps', 'zIndexProps'],
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
            scheduleSetting: this.roomProps.scheduleArrRoom,
            isOpenModalRightNowTemp: false,
            isOpenModalStandByTemp: false,
            isOpenModalSettingRelayTemp: false,
            isOpenModalSchedule: false,
            arrayModes: this.roomProps.arrayModes,
            classArray: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
            },
            objSettingRelayTemp: {},
            objSettingSchedule: {},
        }
    },
    beforeMount() {
        this.COPY_SETTING_ROOM_TO_ARRAY(this.curRoom);
        this.$eventBus.$on('modal_select_mode', this.selectMode);
        this.$eventBus.$on('modal_select_temp', this.selectTemp);
        this.$eventBus.$on('open_modal_setting_relay_temp', this.openSettingRelayTemp);
        this.$eventBus.$on('open_select_stand_by_temp', this.openSelectStandByTemp);
        this.$eventBus.$on('modal_select_relay_temp_name', this.selectRelayTempName);
        this.$eventBus.$on('modal_select_schedule', this.modalSelectSchedule);
    },
    beforeUnmount() {
        this.$eventBus.$off('modal_select_mode', this.selectMode);
        this.$eventBus.$off('modal_select_temp', this.selectTemp);
        this.$eventBus.$off('open_modal_setting_relay_temp', this.openSettingRelayTemp);
        this.$eventBus.$off('modal_select_relay_temp_name', this.selectRelayTempName);
        this.$eventBus.$off('open_select_stand_by_temp', this.openSelectStandByTemp);
        this.$eventBus.$off('modal_select_schedule', this.modalSelectSchedule);
    },
    computed: {
        ...mapState(
            {copySettingRoom: 'copySettingRoom'}
        ),
        itMainBlock: function () {
            return this.curRoom.id === 0;
        },
        curRoomId() {
            return this.curRoom.id;
        },
        curRoomName() {
            return this.curRoom.roomName;
        },
        selectModeText() {
            let filterItem = this.arrayModes.filter(item =>
                item.isSelect
            );
            let textMode = '';
            if (filterItem.length === 1) {
                textMode = filterItem[0]['textMode'];
            }
            return textMode;
        },
    },
    methods: {
        ...mapActions(['COPY_SETTING_ROOM_TO_ARRAY', 'SET_CURRENT_ROOM']),
        closeModal() {
            this.updateArrayModes('close');
            this.$eventBus.$emit('modal_all_setting', 'close', this.curRoom);
        },
        selectMode(selMode) {
            if (selMode === 1) {
                this.selectRightNowTemp('open', this.rightNowTemp);
            }
            this.currentMode = selMode;
        },
        openSelectStandByTemp() {
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
        openSettingRelayTemp() {
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
        modalSelectSchedule(objArg) {
            if (objArg.eventName === 'open') {
                this.openModalSchedule();
            } else if (objArg.eventName === 'close') {
                this.closeModalSchedule();
            } else if (objArg.eventName === 'save') {
                this.saveModalSchedule(objArg);
            }
        },
        openModalSchedule() {
            let scheduleArray = [];
            this.scheduleSetting.map(item => scheduleArray.push({...item}));
            this.objSettingSchedule = {
                roomId: this.curRoom.id,
                curRoom: this.curRoom,
                scheduleArray: scheduleArray,
            }
            this.isOpenModalSchedule = true;
        },
        closeModalSchedule() {
            this.isOpenModalSchedule = false;
        },
        saveModalSchedule(objArg) {
            this.scheduleSetting = [];
            objArg.scheduleArray.map(item => this.scheduleSetting.push({...item}));
            this.isOpenModalSchedule = false;
        },
        async saveSetting() {
            let thisServerUpdateTime = Math.floor(Date.now() / 1000);
            let strSetting = this.generateSettingsText(thisServerUpdateTime);
            let hashStrSetting = strSetting.hashCode();
            if (hashStrSetting < 0) {
                hashStrSetting = -hashStrSetting;
            }
            hashStrSetting = hashStrSetting.toString(16) + ";";
            let objSetting = {
                id: this.curRoom.id,
                currentMode: this.currentMode,
                rightNowTemp: this.rightNowTemp,
                roomsPOutputs: this.roomsPOutputs,
                roomsTsensors: this.roomsTsensors,
                standByTemp: this.standByTemp,
                scheduleSetting: this.scheduleSetting,
                roomName: this.roomName,
                tempName: this.tempName,
                relayName: this.relayName,
                thisServerUpdateTime: thisServerUpdateTime,
                strSetting: strSetting,
                hashStrSetting: hashStrSetting,
            }
            await axios.post('/api/save_setting_to_files', objSetting)
                .then(response => {
                    this.$eventBus.$emit('update_state_rooms');
                    this.updateArrayModes('save');
                })
                .catch(err => {
                    console.log('error /api/save_setting_to_files', err.response.data);
                });
        },
        updateArrayModes(operation) {
            let filterItem = null;
            if (operation === 'save') {
                filterItem = this.arrayModes.find(item => item.id === this.currentMode);
            } else if (operation === 'close') {
                filterItem = this.arrayModes.find(item => item.id === this.curRoom.currentMode);
            }
            this.arrayModes.forEach(item => {
                if (item.id === filterItem.id) {
                    item.isSelect = true;
                    this.currentMode = item.id;
                    this.curRoom.currentMode = item.id;
                } else {
                    item.isSelect = false;
                }
            })
        },
        generateSettingsText(thisServerUpdateTime) {
            let P_or_room_num = this.roomsPOutputs;
            if (this.curRoom.id === 0) {
                P_or_room_num = this.curRoom.id;
            }
            let timeout = "0;0";
            // if (setTimeOutFlag || askTimeOutTime) {
            //     timeout = curTimeOut + ";" + timeOutMode;
            // }
            let strSchedule = '';
            let strScheduleMode = '';
            this.scheduleSetting.forEach(item => {
                strSchedule += String(item.time) + String(item.temp * 10).padStart(3, '0') + ';';
                strScheduleMode += String(item.mode);
            })
            let strSetting = String(this.curRoom.id) + ';' + String(this.currentMode) + ';' + String(this.rightNowTemp) + ';' + timeout + ':' + String(P_or_room_num) + ';' + String(this.roomsTsensors) + ';' + String(this.standByTemp) + ';' + String(this.scheduleSetting.length) + ';' + strSchedule + strScheduleMode + ';';
            return strSetting;
        },
    },
}
</script>


<style scoped>

</style>
