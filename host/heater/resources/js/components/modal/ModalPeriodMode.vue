<template>
    <modal_window
                 :key="'modalMode' + String(room.id)"
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
                                :key="'selectTempMode'  + String(room.id)"
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
    </modal_window>

</template>

<script>
import select_temperature from "../mode/SelectTemperature.vue";
import {mapState} from "vuex";
import modal_window from "./ModalWindow.vue";

export default {
    name: "modal_period_mode",
    components: {modal_window, select_temperature},
    data() {
        return {
            room: undefined,
            beginPeriodStr: this.objProps.beginPeriodStr,
            endPeriodStr: this.objProps.endPeriodStr,
            scheduleItem: this.objProps.scheduleItem,
            scheduleMode: this.objProps.scheduleItem.mode,
            tempMode: this.objProps.scheduleItem.tempMode,
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_setting': true,
                'modal__shadow_background': true,
            },
        }
    },
    beforeMount() {
        this.room = this.copySettingRoom.find(item => item.name === 'currentRoom').value;

    },
    beforeUnmount() {
        this.$eventBus.$off('change_temp_mode', this.changeTempMode);
    },
    methods: {
        selectScheduleMode(selectMode){
            this.scheduleMode = selectMode;
        },
        changeTempMode(objArg){

        },
        saveScheduleMode(){

        },
    },
    computed: {
        ...mapState({copySettingRoom: 'copySettingRoom'}),

    },
}
</script>


<style scoped>

</style>
