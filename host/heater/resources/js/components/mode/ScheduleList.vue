<template>
    <modal_child v-if="isOpenModalPeriod"
                 :key="'modalSchedulePeriod' + String(currentRoom.id) + strIndex"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModalStatusPeriod()" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ currentRoom.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ currentRoom.roomName }}
            </div>
            <div class="modal__title">
                Настройка периода
            </div>
            <div class="modal__title">
                {{ beginPeriodStr + ' - ' + endPeriodStr }}
            </div>

        </template>
        <template #content>
            <div class="modal__temp_block">
                <div class="modal__temp_element">
                    <a href="" @click.prevent="tenHourUp"><img class="modal__temp_img"
                                                               src="/icons/plus.png"/></a>
                    <p>{{ tenHourSchedule }}</p>
                    <a href="" @click.prevent="tenHourDown"><img class="modal__temp_img"
                                                                 src="/icons/minus.png"/></a>
                </div>
                <div class="modal__temp_element">
                    <a href="" @click.prevent="hourUp"><img class="modal__temp_img"
                                                            src="/icons/plus.png"/></a>
                    <p>{{ hourSchedule }}</p>
                    <a href="" @click.prevent="hourDown"><img class="modal__temp_img"
                                                              src="/icons/minus.png"/></a>
                </div>
                <div class="modal__temp_element"><p>:</p></div>
                <div class="modal__temp_element">
                    <a href="" @click.prevent="tenMinuteUp"><img class="modal__temp_img"
                                                                 src="/icons/plus.png"/></a>
                    <p>{{ tenMinuteSchedule }}</p>
                    <a href="" @click.prevent="tenMinuteDown"><img class="modal__temp_img"
                                                                   src="/icons/minus.png"/></a>
                </div>
                <div class="modal__temp_element">
                    <a href="" @click.prevent="minuteUp"><img class="modal__temp_img"
                                                              src="/icons/plus.png"/></a>
                    <p>{{ minuteSchedule }}</p>
                    <a href="" @click.prevent="minuteDown"><img class="modal__temp_img"
                                                                src="/icons/minus.png"/></a>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="modal__schedule_footer_keys">
                <button @click.prevent="savePeriod" class="modal__footer_button">OK</button>
            </div>
        </template>
    </modal_child>

    <modal_child v-if="isOpenModalScheduleMode"
                 :key="'modalMode' + String(currentRoom.id) + strIndex"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModalStatusMode()" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ currentRoom.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ currentRoom.roomName }}
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
                                :key="'selectTempMode'  + String(currentRoom.id) + strIndex"
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

    <div class="modal__schedule_container" v-if="isOpenScheduleList">
        <schedule_item v-for="(item, index) in scheduleArray"
                       :key="'scheduleItem' + this.currentRoom.id + index"
                       :beginPeriodProps=beginPeriodItem(item)
                       :scheduleItemProps=item
                       :scheduleArrayProps=scheduleArray
                       :indexProps=index
        >
        </schedule_item>
    </div>
</template>

<script>
import schedule_item from "./ScheduleItem.vue";
import {mapState} from "vuex";
import modal_child from "../modal/ModalChild.vue";
import select_temperature from "./SelectTemperature.vue";
import {indexOf} from "lodash/array";

export default {
    name: "schedule_list",
    props: ['scheduleArrayProps'],
    components: {select_temperature, modal_child, schedule_item},
    data() {
        return {
            scheduleArray: this.scheduleArrayProps,
            isOpenModalPeriod: false,
            isOpenModalScheduleMode: false,
            isOpenScheduleList:true,
            strIndex: '0',
            tenHourSchedule: 0,
            hourSchedule: 0,
            tenMinuteSchedule: 0,
            minuteSchedule: 0,
            scheduleItem: undefined,
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_period_mode': true,
                'modal__shadow_background': true,
            },
            scheduleMode: undefined,
            tempMode: undefined,
            beforeChangeScheduleMode: undefined,
        }
    },
    created() {
        this.$eventBus.$on('change_schedule_period', this.changeSchedulePeriod);
        this.$eventBus.$on('change_mode_period', this.changeModePeriod);
        this.$eventBus.$on('select_temp_mode', this.selectTempMode);
    },
    beforeDestroy() {
        this.$eventBus.$off('change_schedule_period', this.changeSchedulePeriod);
        this.$eventBus.$off('change_mode_period', this.changeModePeriod);
        this.$eventBus.$off('select_temp_mode', this.selectTempMode);
    },
    computed: {
        ...mapState({
            currentRoom: 'currentRoom'
        }),
        itMainBlock() {
            return this.currentRoom.id !== 0;
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
    methods: {
        fillVariable(itemSchedule){
            this.scheduleItem = itemSchedule;
            this.strIndex = indexOf(itemSchedule);
            this.scheduleMode = this.scheduleItem.mode;
            this.tempMode = this.scheduleItem.temp * 10;
            this.beforeChangeScheduleMode = this.scheduleItem.mode;
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
        closeModalStatusMode() {
            this.isOpenModalScheduleMode = false;
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
                                this.scheduleArray[i].time = comparTime;
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
                                this.scheduleArray[i].time = comparTime;
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
                            this.scheduleArray[i].time = comparTime;
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
            //console.log(this.scheduleArray);
            if (needRenderList) {
                this.rerenderScheduleList();
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
        },
        rerenderScheduleList(){
            //this.$eventBus.$emit('rerender_schedule_list');
        },

    },
}
</script>

<style scoped>

</style>
