<template>
    <modal_child v-if="isOpenModalScheduleMode"
                 :key="'modalMode' + String(room.id) + strIndex"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @fire=on_action @click.prevent="closeModalStatusMode()" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ room.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ room.roomName }}
            </div>
            <div class="modal__title">
                Выбор режима работы для периода
            </div>
            <div class="modal__title">
                {{ beginPeriodStr + ' - ' + endPeriodStr }}
            </div>
            <div class="modal__schedule_mode_select_block">
                <div class="modal__schedule_mode_select_element">
                    <a href="" @click.prevent="selectScheduleMode(0)"><img src="/icons/t2.png"/></a>
                </div>
                <div class="modal__schedule_mode_select_element">
                    <a href="" @click.prevent="selectScheduleMode(1)"><img src="/icons/mode-off.png"/></a>
                </div>
                <div class="modal__schedule_mode_select_element">
                    <a href="" @click.prevent="selectScheduleMode(2)"><img src="/icons/mode-on.png"/></a>
                </div>
            </div>
        </template>
        <template #content>
            <select_temperature v-if="scheduleMode === 0"
                                :key="'selectTempMode'  + String(room.id) + strIndex"
                                :nameSelectModeProps="'changeScheduleItem'"
                                :tempSelectProps="tempMode"
            >
            </select_temperature>

            <div v-else-if="scheduleMode === 1" class="modal__schedule_info">
                <img src="/icons/mode-off.png"/>
                <p>Выключить</p>
            </div>
            <div v-else-if="scheduleMode === 2" class="modal__schedule_info">
                <img src="/icons/mode-on.png"/>
                <p>Включить</p>
            </div>
        </template>
        <template #footer>
            <button @click="saveScheduleMode" class="modal__footer_button">OK</button>
        </template>
    </modal_child>

</template>

<script>
import {defineComponent} from 'vue'
import select_temperature from "../mode/SelectTemperature.vue";
import modal_child from "./ModalChild.vue";
import schedule_list from "../mode/ScheduleList.vue";
import {mapActions, mapState} from "vuex";
import {indexOf} from "lodash/array";

export default {
    name: "modal_schedule_mode",
    components: {schedule_list, modal_child, select_temperature},
    data() {
        return {
            room: this.roomProps,
            nameTempRoom: this.roomProps.roomsTsensorsName,
            nameRelayRoom: this.roomProps.roomsPOutputs,
            beforeNameTempRoom: this.roomProps.roomsTsensorsName,
            beforeNameRelayRoom: this.roomProps.roomsPOutputs,
            nameRelay: '',
            nameTemp: '',
            nameRoom: this.roomProps.roomName,
            beforeNameRoom: this.roomProps.roomName,
            isOpenModalMode: this.isOpenModalModeProps,
            isOpenModalSetting: false,
            isOpenModalStandByTemp: false,
            isOpenModalSchedule: false,
            isOpenScheduleList: false,
            scheduleArray: this.scheduleArrayProps,
            isOpenModalPeriod: false,
            isOpenModalScheduleMode: false,
            arrayTemp: this.$store.state.arrayTemp,
            arrayRelay: this.$store.state.arrayRelay,
            classWindow: this.classProps,
            arrSchedule: this.roomProps.scheduleArrRoom.map(item => ({...item})),
            standByTemp: 0,
            standByTempBeforeChange: 0,
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_setting': true,
                'modal__shadow_background': true,
            },
            componentScheduleKey: 'scheduleList',
            selectMode: null,
            strIndex: '0',
            tenHourSchedule: 0,
            hourSchedule: 0,
            tenMinuteSchedule: 0,
            minuteSchedule: 0,
            scheduleItem: undefined,
            scheduleMode: undefined,
            tempMode: undefined,
            beforeChangeScheduleMode: undefined,
        }
    },
    created() {
        this.standByTemp = this.room.standByTemp * 10;
        this.standByTempBeforeChange = this.standByTemp;
        this.SET_CURRENT_ROOM(this.room);
        this.$eventBus.$on('select_mode_set', this.selectModeSet);
        this.$eventBus.$on('modal_open_setting', this.modalStatusSetting);
        this.$eventBus.$on('modal_open_temp', this.modalStatusStandByTemp);
        this.$eventBus.$on('modal_open_schedule', this.modalStatusSchedule);
        this.$eventBus.$on('rerender_schedule_list', this.rerenderScheduleList);
        this.$eventBus.$on('select_stand_by_temp', this.selectStandByTemp);
        this.$eventBus.$on('change_schedule_period', this.changeSchedulePeriod);
        this.$eventBus.$on('change_mode_period', this.changeModePeriod);
        this.$eventBus.$on('select_temp_mode', this.selectTempMode);
    },
    mounted() {
        document.body.addEventListener("keydown", this.onKeyDown);
    },
    beforeUnmount() {
        document.body.removeEventListener("keydown", this.onKeyDown);
        this.$eventBus.$off('select_mode_set', this.selectModeSet);
        this.$eventBus.$off('modal_open_setting', this.modalStatusSetting);
        this.$eventBus.$off('modal_open_temp', this.modalStatusStandByTemp);
        this.$eventBus.$off('modal_open_schedule', this.modalStatusSchedule);
        this.$eventBus.$off('rerender_schedule_list', this.rerenderScheduleList);
        this.$eventBus.$off('select_stand_by_temp', this.selectStandByTemp);
        this.$eventBus.$off('change_schedule_period', this.changeSchedulePeriod);
        this.$eventBus.$off('change_mode_period', this.changeModePeriod);
        this.$eventBus.$off('select_temp_mode', this.selectTempMode);
    },
    methods: {
        ...mapActions(["SET_CURRENT_ROOM", "SET_NEW_SETTING_ARRAY", "COPY_SCHEDULE"]),

        send_action() {
            this.$emit("fire",{
                "ID" : 0
            })
        },


        closeModalMode() {
            this.$eventBus.$emit('close_modal_mode');
        },
        onKeyDown(event) {
            if (event.key === 'Escape') {
                this.closeModalMode();
            }
        },
        modalStatusSetting(statusModal) {
            this.isOpenModalSetting = statusModal;
        },
        modalStatusStandByTemp(statusModal) {
            this.isOpenModalStandByTemp = statusModal;
        },
        closeStandByTemp() {
            this.standByTemp = this.standByTempBeforeChange;
            this.modalStatusStandByTemp(false);
        },
        modalStatusSchedule(statusModal) {
            if (!statusModal && !this.$isEqualArrays(this.arrSchedule, this.room.scheduleArrRoom)) {
                this.arrSchedule = this.room.scheduleArrRoom.map(item => ({...item}));
            }
            if (!statusModal) {
                //console.log('off select_temp_mode');
                this.$eventBus.$off('select_temp_mode', this.selectTempMode);
            }
            this.isOpenModalSchedule = statusModal;
            this.isOpenScheduleList = statusModal;
        },
        selectModeSet(selMode) {
            this.selectMode = selMode;
        },
        saveSelectMode() {
            if (this.room.selectMode != null) {
                this.$setNewSetting(this.room.id, 'selectMode', this.selectMode);
            }
        },
        tempSensorClick(sideClick) {
            let index = this.arrayTemp.findIndex(obj => obj.value === this.nameTempRoom);
            if (sideClick === 'left') {
                if (index != 0) {
                    this.nameTempRoom = this.arrayTemp[index - 1].value;
                }
            } else {
                if (index != 15) {
                    this.nameTempRoom = this.arrayTemp[index + 1].value;
                }
            }
        },
        tempSensorLeft() {
            this.tempSensorClick('left');
        },
        tempSensorRight() {
            this.tempSensorClick('right');
        },
        relayClick(sideClick) {
            let index = this.arrayRelay.findIndex(item => item === this.nameRelayRoom);
            if (sideClick === 'left') {
                if (index != 0) {
                    this.nameRelayRoom = this.arrayRelay[index - 1];
                }
            } else {
                if (index != 15) {
                    this.nameRelayRoom = this.arrayRelay[index + 1];
                }
            }
        },
        relayLeft() {
            this.relayClick('left')

        },
        relayRight() {
            this.relayClick('right')
        },
        saveRelayTempSetting() {
            if (this.nameRoom !== this.beforeNameRoom) {
                this.$setNewSetting(this.room.id, 'nameRoom', this.nameRoom);
            }
            if (this.nameTempRoom !== this.beforeNameTempRoom) {
                this.$setNewSetting(this.room.id, 'indexTempRoom', this.arrayTemp.filter(item => item.value === this.nameTempRoom).id);
            }
            if (this.nameRelayRoom !== this.beforeNameRelayRoom) {
                this.$setNewSetting(this.room.id, 'indexRelayRoom', this.arrayRelay.filter(item => item === this.nameRelayRoom));
            }
            this.modalStatusSetting(false);
        },
        saveTempSetting() {
            if (this.standByTemp !== this.room.standByTemp * 10) {
                this.$setNewSetting(this.room.id, 'standByTemp', this.standByTemp / 10);
            }
            this.standByTempBeforeChange = this.standByTemp;
            this.modalStatusStandByTemp(false);
        },
        saveScheduleSetting() {
            if (!this.$isEqualArrays(this.arrSchedule, this.room.scheduleArrRoom)) {
                this.room.scheduleArrRoom = this.arrSchedule.map(item => ({...item}));
                //this.$setNewSetting(this.room.id, 'scheduleArray', this.arrSchedule.map(item => ({...item})));
            }
            this.modalStatusSchedule(false);
        },
        rerenderScheduleList() {
            this.isOpenScheduleList = false;
            this.isOpenScheduleList = true;
            if (this.componentScheduleKey.includes('1')) {
                this.componentScheduleKey = this.componentScheduleKey.slice(0, this.componentScheduleKey.length - 1);
            } else {
                this.componentScheduleKey += '1';
            }
        },
        // setNewSetting(idRoomProp, nameProp, ValueProp) {
        //     this.SET_NEW_SETTING_ARRAY({
        //         idRoom: idRoomProp,
        //         name: nameProp,
        //         value: ValueProp
        //     });
        // },
        addScheduleItem() {
            if (this.arrSchedule.length >= 6) {
                return;
            }
            if (this.arrSchedule.length > 1) {
                let curTime = this.arrSchedule[this.arrSchedule.length - 2].time;
                let newItem = {...this.arrSchedule[this.arrSchedule.length - 2]};
                newItem.numStr = this.arrSchedule[this.arrSchedule.length - 1].numStr + 1;
                newItem.time = curTime + 1;
                this.arrSchedule[this.arrSchedule.length - 1].time = newItem.time;
                if (curTime <= 2358) {
                    this.arrSchedule.push(newItem);
                }

            } else if (this.arrSchedule.length === 1) {
                this.arrSchedule[0].time = 2300;
                this.arrSchedule.push({'numStr': 2, 'time': 2301, 'temp': 20, 'mode': 0});
            } else {
                this.arrSchedule.push({'numStr': 1, 'time': 0, 'temp': 20, 'mode': 0});
            }
            this.rerenderScheduleList();

        },
        removeScheduleItem() {
            if (this.arrSchedule.length <= 1) {
                return;
            } else {
                this.arrSchedule.pop();
            }
            this.rerenderScheduleList();
        },
        copyScheduleRoom() {
            this.COPY_SCHEDULE({
                nameRoom: this.room.roomName,
                scheduleRoom: this.arrSchedule.map(item => ({...item})),
            });
        },
        pasteScheduleRoom() {
            if (this.copySchedule.scheduleRoom.length !== 0) {
                this.arrSchedule = this.copySchedule.scheduleRoom.map(item => ({...item}));
            }
            this.rerenderScheduleList();
        },
        selectStandByTemp(temp) {
            //console.log('standByTemp = ' + temp);
            this.standByTemp = temp;
        },
        fillVariable(itemSchedule){
            this.scheduleItem = itemSchedule;
            this.strIndex = indexOf(this.scheduleItem);
            this.scheduleMode = this.scheduleItem.mode;
            this.tempMode = this.scheduleItem.temp * 10;
            this.beforeChangeScheduleMode = this.scheduleItem.mode;
            this.endPeriod = this.scheduleItem.time;
            this.beforeChangeEndPeriod = this.endPeriod;
            this.updatePeriod();
        },
        changeSchedulePeriod(itemSchedule) {
            this.fillVariable(itemSchedule);
            this.isOpenModalPeriod = true;
        },
        changeModePeriod(itemSchedule) {
            this.fillVariable(itemSchedule);
            this.isOpenModalScheduleMode = true;
        },
        beginPeriodItem(curItem) {
            let beginPeriod = 0;
            if (this.scheduleArray.indexOf(curItem) !== 0) {
                beginPeriod = curItem.time;
            }
            return beginPeriod;
        },
        periodToStr(curPeriod) {
            let arrayPeriod = String(curPeriod).split('');
            let countZero = 4 - arrayPeriod.length;
            for (let i = 1; i <= countZero; i++) {
                arrayPeriod.unshift('0');
            }
            arrayPeriod.splice(2, 0, ':');
            return arrayPeriod.join('');
        },
        updatePeriod() {
            this.tenHourSchedule = Math.trunc(this.endPeriod / 1000);
            this.hourSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000) / 100);
            this.tenMinuteSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100) / 10);
            this.minuteSchedule = (this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100 - this.tenMinuteSchedule * 10);
        },
        closeModalStatusPeriod() {
            this.isOpenModalPeriod = false;
            if (this.beforeChangeEndPeriod !== this.endPeriod) {
                this.endPeriod = this.beforeChangeEndPeriod;
                this.updatePeriod();
            }
        },
        changeEndPeriod(operNumber, operation) {
            let strPeriod = String(this.endPeriod);
            let curHour = Number(strPeriod.slice(0, strPeriod.length - 2));
            let newHour = curHour;
            let curMinute = Number(strPeriod.slice(strPeriod.length - 2));
            let newMinute = curMinute;
            if (operation === 'up') {
                if (operNumber <= 10) {
                    let afterOperMinute = curMinute + operNumber;
                    if (afterOperMinute >= 60) {
                        newMinute = afterOperMinute - 60;
                        newHour = curHour + 1;
                    } else {
                        newMinute = afterOperMinute;
                    }
                } else {
                    let afterOperHour = curHour + operNumber / 100;
                    if (afterOperHour <= 23) {
                        newHour = afterOperHour;
                    } else {
                        newHour = 24;
                    }
                }
                if (newHour >= 24) {
                    newHour = 0;
                    newMinute = 0;
                }
            } else {
                if (operNumber <= 10) {
                    let afterOperMinute = curMinute - operNumber;
                    if (afterOperMinute < 0) {
                        newMinute = 60 + afterOperMinute;
                        newHour = curHour - 1;
                    } else {
                        newMinute = afterOperMinute;
                    }
                } else {
                    let afterOperHour = curHour - operNumber / 100;
                    if (afterOperHour > 0) {
                        newHour = afterOperHour;
                    } else {
                        newHour = 0;
                    }
                }
                if (newHour <= 0) {
                    newHour = 0;
                }
            }
            this.endPeriod = newHour * 100 + newMinute;
            this.updatePeriod();
        },
        tenHourUp() {
            this.changeEndPeriod(1000, 'up');
        },
        tenHourDown() {
            this.changeEndPeriod(1000, 'down');
        },
        hourUp() {
            this.changeEndPeriod(100, 'up');
        },
        hourDown() {
            this.changeEndPeriod(100, 'down');
        },
        tenMinuteUp() {
            this.changeEndPeriod(10, 'up');
        },
        tenMinuteDown() {
            this.changeEndPeriod(10, 'down');
        },
        minuteUp() {
            this.changeEndPeriod(1, 'up');
        },
        minuteDown() {
            this.changeEndPeriod(1, 'down');
        },
        setSettingPeriod() {

        },
        clickChangePeriod() {
            this.isOpenModalPeriod = this.scheduleArray.length !== this.scheduleItem.numStr;
        },
        clickChangeMode() {
            this.beforeChangeScheduleMode = this.scheduleMode;
            this.isOpenModalScheduleMode = true;
        },
        isSelectMode(mode) {
            if (this.scheduleMode === mode) {
                return true;
            } else {
                return false;
            }
        },
        selectScheduleMode(mode) {
            this.scheduleMode = mode;
        },
        selectTempMode(curTempMode) {
            console.log('this.tempMode = ' + this.tempMode);
            this.tempMode = curTempMode;
        },
        savePeriod() {
            let needRenderList = false;
            if (this.beforeChangeEndPeriod !== this.endPeriod) {
                let indexItem = this.scheduleArray.indexOf(this.scheduleItem);
                let comparTime = this.endPeriod;
                let indexItemDelete = null;
                if (indexItem > 0) {
                    let prevItem = this.scheduleArray[indexItem - 1];
                    if (prevItem.time >= this.endPeriod) {
                        this.endPeriod = prevItem.time + 1;
                        for (let i = indexItem; i <= this.scheduleArray.length - 1; i++) {
                            if (this.scheduleArray[i].time <= comparTime) {
                                //this.scheduleArray[i].time = comparTime;
                                console.log('scheduleArray[i] before');
                                console.log(this.scheduleArray[i]);
                                this.scheduleArray[i] = {
                                    ...this.scheduleArray[i],
                                    time: comparTime
                                }
                                console.log('scheduleArray[i] after');
                                console.log(this.scheduleArray[i]);
                            }
                            comparTime += 1;
                            if (this.scheduleArray[i].time > 2359 && indexItemDelete === null) {
                                indexItemDelete = i;
                            }
                        }
                    } else {
                        this.scheduleArray[indexItem].time = this.endPeriod;
                        comparTime = this.endPeriod + 1;
                        for (let i = indexItem; i <= this.scheduleArray.length - 1; i++) {
                            if (this.scheduleArray[i].time <= comparTime) {
                                //this.scheduleArray[i].time = comparTime;
                                console.log('scheduleArray[i] before');
                                console.log(this.scheduleArray[i]);
                                this.scheduleArray[i] = {
                                    ...this.scheduleArray[i],
                                    time: comparTime
                                }
                                console.log('scheduleArray[i] after');
                                console.log(this.scheduleArray[i]);
                            }
                            comparTime += 1;
                            if (this.scheduleArray[i].time >= 2358 && indexItemDelete === null) {
                                indexItemDelete = i;
                            }
                        }
                    }
                } else {
                    this.scheduleArray[indexItem].time = this.endPeriod;
                    comparTime = this.endPeriod + 1;
                    for (let i = indexItem; i <= this.scheduleArray.length - 1; i++) {
                        if (this.scheduleArray[i].time <= comparTime) {
                            //this.scheduleArray[i].time = comparTime;
                            console.log('scheduleArray[i] before');
                            console.log(this.scheduleArray[i]);
                            this.scheduleArray[i] = {
                                ...this.scheduleArray[i],
                                time: comparTime
                            }
                            console.log('scheduleArray[i] after');
                            console.log(this.scheduleArray[i]);
                        }
                        comparTime += 1;
                        if (this.scheduleArray[i].time >= 2358 && indexItemDelete === null) {
                            indexItemDelete = i;
                        }
                    }
                }
                if (indexItemDelete !== null) {
                    this.scheduleArray.splice(indexItemDelete, this.scheduleArray.length - indexItemDelete);
                }
                this.scheduleItem.time = this.endPeriod;
                this.beforeChangeEndPeriod = this.endPeriod;
                needRenderList = true;
            }
            console.log('scheduleArray');
            console.log(this.scheduleArray);
            console.log('scheduleItem');
            console.log(this.scheduleItem);
            console.log('endPeriod');
            console.log(this.endPeriod);
            if (needRenderList) {
                // this.rerenderScheduleList();
            }
            this.closeModalStatusPeriod();
        },
        saveScheduleMode() {
            if (this.scheduleMode !== this.beforeChangeScheduleMode || this.scheduleItem.mode !== this.tempMode) {
                if (this.scheduleMode === 0) {
                    this.scheduleItem.temp = this.tempMode / 10;
                    this.scheduleArray[this.indexProps] = {
                        ...this.scheduleArray[this.indexProps],
                        temp: this.tempMode / 10
                    }
                }
                this.scheduleItem.mode = this.scheduleMode;
                this.scheduleArray[this.indexProps] = {
                    ...this.scheduleArray[this.indexProps],
                    ...this.scheduleArray[this.indexProps],
                    mode: this.scheduleMode
                }
            }
            this.closeModalStatusMode();
            this.some_prop = 2;
        },
        rerenderScheduleList(){
            //this.$eventBus.$emit('rerender_schedule_list');
        },
    },
    computed: {
        ...mapState({
            copySchedule: 'copySchedule', arrayNewSetting: 'arrayNewSetting'
        }),

        // // inup -> title set ID
        // some_prop : {
        //     get : function() {
        //         return this.obk.title;
        //     },
        //     set : function(old_val,new_val) {
        //        /// this.cur_id = parseInt(newVal);
        //     }
        // },

        itMainBlock() {
            return this.room.id === 0;
        },
        itMainBlock() {
            return this.room.id !== 0;
        },
        beginPeriodStr() {
            if (this.scheduleItem === undefined) {
                return '00:00';
            }
            let indexItem = this.scheduleArray.indexOf(this.scheduleItem);
            if (indexItem === 0){
                return '00:00';
            } else {
                return this.periodToStr(this.scheduleArray[indexItem - 1].time);
            }
        },
        endPeriodStr() {
            if (this.scheduleItem === undefined) {
                return '23:59';
            }
            let indexItem = this.scheduleArray.indexOf(this.scheduleItem);
            if (indexItem === this.scheduleArray.length - 1){
                return '23:59';
            } else {
                return this.periodToStr(this.scheduleArray[indexItem].time);
            }
        },
    },
}
</script>


<style scoped>

</style>
