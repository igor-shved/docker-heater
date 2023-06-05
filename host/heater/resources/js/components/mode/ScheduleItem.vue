<template>
    <div class="modal__schedule_block">
        <div class="modal__schedule_element">
            <div class="modal__schedule_info">
                <img class="modal__temp_img" src="/icons/mode-schedule.png"/>
                <p>{{ numStr }}</p>
            </div>
        </div>
        <a href="" class="modal__schedule_element" @click.prevent="clickChangePeriod" v-if="!isLastItem">
            <p class="modal__hide_text">{{ beginPeriodStr }}</p>
            <p>-</p>
            <p :class="classEndPeriod">{{ endPeriodStr }}</p>
        </a>
        <div v-else class="modal__schedule_element">
            <p class="modal__hide_text">{{ beginPeriodStr }}</p>
            <p>-</p>
            <p :class="classEndPeriod">{{ endPeriodStr }}</p>
        </div>
        <a href="" class="modal__schedule_element" @click.prevent="clickChangeMode">
            <div class="modal__schedule_info">
                <img :src="imgPeriod"/>
                <p>{{ scheduleTemp }}</p>
            </div>
        </a>
    </div>

</template>

<script>
// import modal_child from "../modal/ModalChild.vue";
// import select_temperature from "./SelectTemperature.vue";
import {mapState} from "vuex";

export default {
    name: "schedule_item",
    props: ['scheduleItemProps', 'beginPeriodProps', 'scheduleArrayProps', 'indexProps'],
    data() {
        return {
            scheduleArray: this.scheduleArrayProps,
            isLastItem: false,
            scheduleItem: this.scheduleItemProps,
            numStr: this.scheduleItemProps.numStr,
            beginPeriod: this.beginPeriodProps,
            endPeriod: this.scheduleItemProps.time,
            beforeChangeEndPeriod: this.scheduleItemProps.time,
            classEndPeriod: {
                'modal__hide_text': false,
            },
        }
    },
    created() {
        this.isLastItem = this.scheduleArray.length === this.scheduleItem.numStr;
        if (this.isLastItem) {
            this.endPeriod = 2359;
            this.classEndPeriod.modal__hide_text = this.isLastItem;
        }
    },
    computed: {
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
        imgPeriod() {
            if (this.scheduleItem.mode === 0) {
                return '/icons/t-small.png';
            } else if (this.scheduleItem.mode === 1) {
                return '/icons/s_mode-off.png';
            } else if (this.scheduleItem.mode === 2) {
                return '/icons/s_mode-on.png';
            }
        },
    },
    methods: {
        clickChangePeriod(){
            this.$eventBus.emit('change_schedule_period', this.scheduleItem);
        },
        clickChangeMode(){
            this.$eventBus.emit('change_mode_period', this.scheduleItem);
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
    },
}
</script>

<style scoped>

</style>
