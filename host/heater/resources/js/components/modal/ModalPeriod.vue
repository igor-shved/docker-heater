<template>
    <modal_window
        :key="'modalPeriod' + String(room.id)"
        :classArrayProps="classArray"
        :zIndexProps="zIndexProps"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closePeriod" class="modal__close">X</a>
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
    </modal_window>

</template>

<script>
import modal_window from "./ModalWindow.vue";
import {mapState} from "vuex";

export default {
    name: "modal_period",
    props: ['objProps', 'zIndexProps'],
    components: {modal_window},
    data() {
        return {
            room: undefined,
            scheduleItem: this.objProps.scheduleItem,
            classArray: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
            },
            beginPeriodStr: this.objProps.beginPeriodStr,
            endPeriodStr: this.objProps.endPeriodStr,
            endPeriod: this.objProps.endPeriod,
            tenHourSchedule: 0,
            hourSchedule: 0,
            tenMinuteSchedule: 0,
            minuteSchedule: 0,
        }
    },
    created() {
        this.room = this.copySettingRoom.find(item => item.name === 'currentRoom').value;
        this.updatePeriod();
    },
    beforeUnmount() {
    },
    computed: {
        ...mapState({copySettingRoom: 'copySettingRoom'}),
        itMainBlock() {
            return this.room.id === 0;
        },
    },
    methods: {
        updatePeriod() {
            this.tenHourSchedule = Math.trunc(this.endPeriod / 1000);
            this.hourSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000) / 100);
            this.tenMinuteSchedule = Math.trunc((this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100) / 10);
            this.minuteSchedule = (this.endPeriod - this.tenHourSchedule * 1000 - this.hourSchedule * 100 - this.tenMinuteSchedule * 10);
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
        closePeriod() {
            let objArg = {
                eventName: 'close',
                time: this.endPeriod,
            };
            this.$eventBus.$emit('change_period', objArg);
        },
        savePeriod() {
            let objArg = {
                eventName: 'save',
                time: this.endPeriod,
                scheduleItem: this.scheduleItem,
            };
            this.$eventBus.$emit('change_period', objArg);
        },
    },
}
</script>


<style scoped>

</style>
