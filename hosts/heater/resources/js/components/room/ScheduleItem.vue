<template>
    <modal_child v-if="isOpenModalPeriod"
                 :key="'modalSchedulePeriod'"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="modalStatusPeriod(false)" class="modal__close">X</a>
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
                 :key="'modalMode'"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="modalStatusMode(false)" class="modal__close">X</a>
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
                :key="'selectTempMode'"
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


    <div class="modal__schedule_block">
        <div class="modal__schedule_element">
            <div class="modal__schedule_info">
                <img class="modal__temp_img" src="/icons/mode-schedule.png"/>
                <p>{{ numStr }}</p>
            </div>
        </div>
        <a href="" class="modal__schedule_element" @click.prevent="clickChangePeriod">
            <p class="modal__hide_text">{{ beginPeriodStr }}</p>
            <p>-</p>
            <p :class="classEndPeriod">{{ endPeriodStr }}</p>
        </a>
        <a href="" class="modal__schedule_element" @click.prevent="clickChangeMode">
            <div class="modal__schedule_info">
                <img :src="imgPeriod"/>
                <p>{{ scheduleTemp }}</p>
            </div>
        </a>
    </div>

</template>

<script>
import modal_child from "../modal/ModalChild.vue";
import select_temperature from "../mode/SelectTemperature.vue";
import {mapState} from "vuex";

export default {
    name: "schedule_item",
    props: ['scheduleItemProps', 'beginPeriodProps', 'arrayProps'],
    components: {modal_child, select_temperature},
    data() {
        return {
            isOpenModalPeriod: false,
            isOpenModalScheduleMode: false,
            scheduleItem: this.scheduleItemProps,
            scheduleMode: this.scheduleItemProps.mode,
            numStr: this.scheduleItemProps.numStr,
            beginPeriod: this.beginPeriodProps,
            endPeriod: this.scheduleItemProps.time,
            beforeChangeEndPeriod: this.scheduleItemProps.time,
            tenHourSchedule: 0,
            hourSchedule: 0,
            tenMinuteSchedule: 0,
            minuteSchedule: 0,
            arraySchedule: this.arrayProps,
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_period_mode': true,
                'modal__shadow_background': true,
            },
            classEndPeriod: {
                'modal__hide_text': false,
            },
            lengthArray: this.arrayProps.length,
            tempMode: this.scheduleItemProps.temp * 10,
            beforeChangeScheduleMode: this.scheduleItemProps.mode,
        }
    },
    created() {
        let lastItemPeriod = this.lengthArray === this.scheduleItem.numStr;
        if (lastItemPeriod) {
            this.endPeriod = 2359;
            this.classEndPeriod.modal__hide_text = lastItemPeriod;
        }
        this.$eventBus.$on('modal_set_setting_period', this.setSettingPeriod);
        this.$eventBus.$on('select_temp_mode', this.selectTempMode);
        this.updatePeriod();
    },
    beforeDestroy() {
        this.$eventBus.$off('modal_set_setting_period', this.setSettingPeriod);
        this.$eventBus.$off('select_temp_mode', this.selectTempMode);
    },
    computed: {
        ...mapState({
            currentRoom: 'currentRoom',
        }),
        itMainBlock() {
            return this.currentRoom.id !== 0;
        },
        imgPeriod() {
            if (this.scheduleItem.mode === 0) {
                return '/icons/t-small.png';
            } else if (this.scheduleItem.mode === 1) {
                return '/icons/s_mode-off.png';
            } else if (this.scheduleItem.mode === 2) {
                return '/icons/s_mode-on.png';
            }
        },
        scheduleTemp() {
            if (this.scheduleItem.mode === 0) {
                return this.scheduleItem.temp + '°c';
            } else {
                return '';
            }
        },
        beginPeriodStr() {
            return this.periodToStr(this.beginPeriod);
        },
        endPeriodStr() {
            return this.periodToStr(this.endPeriod);
        },
    },
    methods: {
        updatePeriod() {
            this.tenHourSchedule = Math.trunc(this.endPeriod / 1000);
            this.hourSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000) / 100);
            this.tenMinuteSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100) / 10);
            this.minuteSchedule = (this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100 - this.tenMinuteSchedule * 10);
        },
        modalStatusPeriod(statusModal) {
            if (!statusModal && this.beforeChangeEndPeriod !== this.endPeriod) {
                this.endPeriod = this.beforeChangeEndPeriod;
                this.updatePeriod();
            }
            this.isOpenModalPeriod = statusModal;
        },
        modalStatusMode(statusModal) {
            this.isOpenModalScheduleMode = statusModal;
            this.$eventBus.$emit('rerender_schedule_list');
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
            this.isOpenModalPeriod = this.lengthArray !== this.scheduleItem.numStr;
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
        selectTempMode(curTempMode){
            this.tempMode = curTempMode;
        },
        savePeriod() {
            let needRenderList = false;
            if (this.beforeChangeEndPeriod !== this.endPeriod) {
                let indexItem = this.arraySchedule.indexOf(this.scheduleItem);
                let comparTime = this.endPeriod;
                let indexItemDelete = null;
                if (indexItem > 0) {
                    let prevItem = this.arraySchedule[indexItem - 1];
                    if (prevItem.time >= this.endPeriod) {
                        this.endPeriod = prevItem.time + 1;
                        for (let i = indexItem; i <= this.arraySchedule.length - 1; i++) {
                            if (this.arraySchedule[i].time <= comparTime) {
                                this.arraySchedule[i].time = comparTime;
                            }
                            comparTime += 1;
                            if (this.arraySchedule[i].time > 2359 && indexItemDelete === null) {
                                indexItemDelete = i;
                            }
                        }
                    } else {
                        this.arraySchedule[indexItem].time = this.endPeriod;
                        comparTime = this.endPeriod + 1;
                        for (let i = indexItem; i <= this.arraySchedule.length - 1; i++) {
                            if (this.arraySchedule[i].time <= comparTime) {
                                this.arraySchedule[i].time = comparTime;
                            }
                            comparTime += 1;
                            if (this.arraySchedule[i].time >= 2358 && indexItemDelete === null) {
                                indexItemDelete = i;
                            }
                        }
                    }
                } else {
                    this.arraySchedule[indexItem].time = this.endPeriod;
                    comparTime = this.endPeriod + 1;
                    for (let i = indexItem; i <= this.arraySchedule.length - 1; i++) {
                        if (this.arraySchedule[i].time <= comparTime) {
                            this.arraySchedule[i].time = comparTime;
                        }
                        comparTime += 1;
                        if (this.arraySchedule[i].time >= 2358 && indexItemDelete === null) {
                            indexItemDelete = i;
                        }
                    }
                }
                if (indexItemDelete !== null) {
                    this.arraySchedule.splice(indexItemDelete, this.arraySchedule.length - indexItemDelete);
                }
                this.scheduleItem.time = this.endPeriod;
                this.beforeChangeEndPeriod = this.endPeriod;
                needRenderList = true;
            }
            if (needRenderList) {
                this.$eventBus.$emit('rerender_schedule_list');
            }
            this.modalStatusPeriod(false);
        },
        saveScheduleMode() {
            let needRenderList = false;
            if (this.scheduleMode !== this.beforeChangeScheduleMode || this.scheduleItem.mode !== this.tempMode) {
                if (this.scheduleMode === 0) {
                    this.scheduleItem.temp = this.tempMode / 10;
                }
                this.scheduleItem.mode = this.scheduleMode;
                needRenderList = true;
            }
            if (needRenderList) {
                this.$eventBus.$emit('rerender_schedule_list');
            }
            this.modalStatusMode(false);
        },
    },
}
</script>

<style scoped>

</style>
