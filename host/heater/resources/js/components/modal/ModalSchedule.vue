<template>
    <modal_window
        :key="'modalSchedule'+String(this.objSettingProps.roomId)"
        :classArrayProps="classArrayProps"
        :zIndexProps="zIndex + 1"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModalSchedule" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ roomId }}
                </div>
            </div>
            <div class="modal__title">
                {{ curRoom.roomName }}
            </div>
            <div class="modal__title">
                Расписание
            </div>
        </template>
        <template #content>
            <div class="modal__schedule_container">
                <schedule_item v-for="(item, index) in scheduleArray"
                               :key="'scheduleItem' + index + roomId"
                               :scheduleItemProps=item
                               :roomIdProps="roomId"
                               :isLastItemProps=isLastItemArray(item)
                               :beginPeriodProps=beginPeriod(item)
                               :endPeriodProps=endPeriod(item)
                               :zIndexProps="zIndexProps"
                >
                </schedule_item>
            </div>
        </template>
        <template #footer>
            <div class="modal__schedule_footer">
                <div class="modal__schedule_footer_keys">
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="addScheduleItem"><img src="/icons/add-btn.png"/></a>
                        <p>Добавить</p>
                    </div>
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="removeScheduleItem"><img src="/icons/del-btn.png"/></a>
                        <p>Удалить</p>
                    </div>
                </div>
                <div class="modal__schedule_footer_keys">
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="copyScheduleRoom"><img src="/icons/copy.png"/></a>
                        <p>Копировать</p>
                    </div>
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="pasteScheduleRoom"><img src="/icons/paste.png"/></a>
                        <p>Вставить</p>
                    </div>
                </div>
                <div class="modal__schedule_footer_keys">
                    <button @click.prevent="saveScheduleRoom" class="modal__footer_button">OK</button>
                </div>
            </div>
        </template>
    </modal_window>

</template>

<script>
import modal_window from "./ModalWindow.vue";
import {mapActions, mapState} from "vuex";
import schedule_item from "../mode/ScheduleItem.vue";

export default {
    name: "modal_schedule",
    components: {schedule_item, modal_window},
    emits: ['close_modal_schedule'],
    props: ['objSettingProps', 'classArrayProps', 'zIndexProps'],
    data() {
        return {
            curRoom: this.objSettingProps.curRoom,
            roomId: this.objSettingProps.roomId,
            zIndex: this.zIndexProps,
            scheduleArray: this.objSettingProps.scheduleArray,
            scheduleArrayBeforeChange: this.objSettingProps.scheduleArray,
            objArg: {
                eventName: '',
                scheduleArray: this.objSettingProps.scheduleArray
            },
        }
    },
    beforeMount() {
        this.$eventBus.$on('change_period_item', this.changePeriodItem);
    },
    beforeUnmount() {
        this.$eventBus.$off('change_period_item', this.changePeriodItem);
    },
    computed: {
        ...mapState(['copySchedule']),
        itMainBlock() {
            return this.roomId === 0;
        },
    },
    methods: {
        ...mapActions(['COPY_SCHEDULE']),
        beginPeriod(item) {
            let indexItem = this.scheduleArray.indexOf(item);
            if (indexItem === 0) {
                return 0;
            } else {
                return this.scheduleArray[indexItem - 1].time;
            }
        },
        endPeriod(item) {
            let indexItem = this.scheduleArray.indexOf(item);
            if (indexItem === this.scheduleArray.length - 1) {
                return 2359;
            } else {
                return item.time;
            }
        },
        isLastItemArray(item) {
            if (this.scheduleArray.indexOf(item) === this.scheduleArray.length - 1) {
                return true;
            } else {
                return false;
            }
        },
        addScheduleItem() {
            if (this.scheduleArray.length >= 6) {
                return;
            }

            if (this.scheduleArray.length > 1) {
                let curTime = this.scheduleArray[this.scheduleArray.length - 2].time;
                let newItem = {...this.scheduleArray[this.scheduleArray.length - 2]};
                newItem.numStr = this.scheduleArray[this.scheduleArray.length - 1].numStr + 1;
                newItem.time = curTime + 1;
                this.scheduleArray[this.scheduleArray.length - 1].time = newItem.time;
                if (curTime <= 2358) {
                    this.scheduleArray.push(newItem);
                }
            } else if (this.scheduleArray.length === 1) {
                this.scheduleArray[0].time = 2300;
                let newItem = {'numStr': 2, 'time': 2301, 'temp': 20, 'mode': 0};
                this.scheduleArray.push(newItem);
            } else {
                let newItem = {'numStr': 1, 'time': 0, 'temp': 20, 'mode': 0};
                this.scheduleArray.push(newItem);
            }
        },
        removeScheduleItem() {
            if (this.scheduleArray.length <= 1) {
                return;
            } else {
                this.scheduleArray.pop();
            }
        },
        copyScheduleRoom() {
            this.scheduleArrayCopy = [];
            this.scheduleArray.map(item => this.scheduleArrayCopy.push({...item}));
            this.COPY_SCHEDULE({
                roomId: this.roomId,
                curRoom: this.curRoom,
                scheduleRoom: this.scheduleArrayCopy,
            });
        },
        pasteScheduleRoom() {
            if (this.copySchedule.scheduleRoom.length !== 0 && this.copySchedule.roomId !== this.roomId) {
                this.scheduleArray = [];
                this.copySchedule.scheduleRoom.map(item => this.scheduleArray.push({...item}));
            }
        },
        saveScheduleRoom() {
            this.$eventBus.$emit('modal_select_schedule', {eventName: 'save', scheduleArray: this.scheduleArray});
        },
        closeModalSchedule() {
            this.$emit('close_modal_schedule');
        },
        changeArray(indexItem, comparTime, scheduleArray) {
            let prevItemSelect = undefined;
            if (indexItem !== 0){
                prevItemSelect = scheduleArray[indexItem - 1];
            }
            let indexItemDelete = null;
            for (let i = indexItem; i <= scheduleArray.length - 1; i++) {
                if (scheduleArray[i].time <= comparTime) {
                    scheduleArray[i].time = comparTime;
                }
                if (prevItemSelect !== undefined && scheduleArray[i].time > 2359 && indexItemDelete === null) {
                    if (prevItemSelect.time > 2357) {
                        indexItemDelete = i;
                    } else {
                        scheduleArray[i].time = prevItemSelect.time + 1;
                    }
                }
                comparTime += 1;
                prevItemSelect = scheduleArray[i];
            }
            if (indexItemDelete !== null) {
                scheduleArray.splice(indexItemDelete, scheduleArray.length - indexItemDelete);
            }
        },
        changePeriodItem(objArg) {
            let indexItem = this.scheduleArray.indexOf(objArg.scheduleItem);
            this.scheduleArray[indexItem].time = objArg.time;
            let comparTime = objArg.time;
            let indexItemDelete = null;
            if (indexItem > 0) {
                let prevItem = this.scheduleArray[indexItem - 1];
                if (prevItem.time >= objArg.time) {
                    this.scheduleArray[indexItem].time = prevItem.time + 1;
                    this.changeArray(indexItem, comparTime, this.scheduleArray);
                } else {
                    comparTime = objArg.time + 1;
                    this.changeArray(indexItem + 1, comparTime, this.scheduleArray);
                }
            } else {
                comparTime = objArg.time + 1;
                this.changeArray(indexItem + 1, comparTime, this.scheduleArray);
            }
        },
    },
}
</script>


<style scoped>

</style>
