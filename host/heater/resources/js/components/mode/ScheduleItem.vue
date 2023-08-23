<template>
    <div class="modal__schedule_block">

        <div class="modal__schedule_element">
            <div class="modal__schedule_info">
                <img class="modal__temp_img" src="/icons/mode-schedule.png"/>
                <p>{{ numStr }}</p>
            </div>
        </div>
        <a href="" class="modal__schedule_element" @click.prevent="openPeriod">
            <p class="modal__hide_text">{{ beginPeriodStr }}</p>
            <p>-</p>
            <p :class="classEndPeriod">{{ endPeriodStr }}</p>
        </a>
        <a href="" class="modal__schedule_element" @click.prevent="openMode">
            <div class="modal__schedule_info">
                <img :src="imgPeriod"/>
                <p>{{ scheduleTemp }}</p>
            </div>
        </a>
    </div>

    <modal_period v-if="isOpenModalPeriod"
                  :key="'modalPeriod' + this.scheduleItemProps.numStr"
                  :objProps="this.objPropsPeriod"
                  :zIndexProps="this.zIndexProps + 1"
    >
    </modal_period>

    <modal_period_mode v-if="isOpenModalPeriodMode"
                       :key="'modalPeriod' + this.scheduleItemProps.numStr"
                       :objProps="this.objPropsMode"
                       :zIndexProps="this.zIndexProps + 1"
    >
    </modal_period_mode>

</template>

<script>
import modal_period_mode from "../modal/ModalPeriodMode.vue";
import modal_period from "../modal/ModalPeriod.vue"

export default {
    name: "schedule_item",
    components: {modal_period, modal_period_mode},
    props: ['scheduleItemProps', 'roomIdProps', 'isLastItemProps', 'beginPeriodProps', 'endPeriodProps', 'zIndexProps'],
    data() {
        return {
            scheduleItem: this.scheduleItemProps,
            numStr: this.scheduleItemProps.numStr,
            isLastItem: this.isLastItemProps,
            beginPeriod: this.beginPeriodProps,
            endPeriod: this.endPeriodProps,
            classEndPeriod: {
                'modal__hide_text': false,
            },
            objPropsPeriod: {},
            objPropsMode: {},
            isOpenModalPeriod: false,
            selectTime: this.scheduleItemProps.time,
            isOpenModalPeriodMode: false,
            selectMode: this.scheduleItemProps.mode,
            classArray: {
                'modal__shadow_main': true,
                'modal__shadow_background': true,
            },
        }
    },
    beforeMount() {
        this.updateData();
        this.$eventBus.$on('change_period', this.changePeriod);
        this.$eventBus.$on('change_mode', this.changeMode);
    },
    beforeUnmount() {
        this.$eventBus.$off('change_period', this.changePeriod);
        this.$eventBus.$off('change_mode', this.changeMode);
    },
    updated() {
        //console.log('update item', this.scheduleItem);
        this.updateData();
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
            } else if (this.scheduleItem.mode === 2) {
                return '/icons/s_mode-off.png';
            } else if (this.scheduleItem.mode === 1) {
                return '/icons/s_mode-on.png';
            }
        },
    },
    watch: {
        scheduleItem: {
            deep: true,
            handler(val, oldVal) {
                //console.log('scheduleItem new', val, 'scheduleItem old');
                //this.updateData();
                //this.updateObjProps();
            }
        },
    },
    methods: {
        updateData() {
            this.scheduleItem = this.scheduleItemProps;
            this.numStr = this.scheduleItemProps.numStr;
            this.isLastItem = this.isLastItemProps;
            this.beginPeriod = this.beginPeriodProps;
            this.endPeriod = this.endPeriodProps;
            if (this.isLastItem) {
                this.classEndPeriod.modal__hide_text = true;
            } else {
                this.classEndPeriod.modal__hide_text = false;
            }
            this.selectTime = this.scheduleItem.time;
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
        changePeriod(objArg) {
            if (objArg.eventName === 'close') {
                this.closePeriod();
            } else if (objArg.eventName === 'open') {
                this.openPeriod();
            } else if (objArg.eventName === 'save') {
                this.savePeriod(objArg);
            }
        },
        openPeriod() {
            if (!this.isLastItem) {
                this.objPropsPeriod = {
                    scheduleItem: this.scheduleItem,
                    endPeriod: this.endPeriod,
                    beginPeriodStr: this.periodToStr(this.beginPeriod),
                    endPeriodStr: this.periodToStr(this.endPeriod),
                }
                this.isOpenModalPeriod = true;
            }
        },
        closePeriod() {
            this.isOpenModalPeriod = false;
        },
        savePeriod(objArg) {
            this.isOpenModalPeriod = false;
            if (objArg.scheduleItem === this.scheduleItem) {
                this.$eventBus.$emit('change_period_item', objArg);
            }
        },
        openMode() {
            this.objPropsMode = {
                scheduleItem: this.scheduleItem,
                scheduleTemp: this.scheduleItem.temp,
                beginPeriodStr: this.periodToStr(this.beginPeriod),
                endPeriodStr: this.periodToStr(this.endPeriod),
            }
            this.isOpenModalPeriodMode = true;
        },
        closeMode() {
            this.isOpenModalPeriodMode = false;
        },
        changeMode(objArg) {
            if (objArg.eventName = "close"){
                this.closeMode();
            } else if (objArg.eventName = "save") {
                this.scheduleItem = {...objArg.scheduleItem};
                this.updateData();
            }
        },
    },
}
</script>

<style scoped>

</style>
