<template>
    <modal_window
        :key="'modalRightNowTemp'+String(curRoom.id)"
        :classArrayProps="classArrayProps"
        :zIndexProps="this.zIndexProps"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModal" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ curRoom.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ curRoom.roomName }}
            </div>
            <div class="modal__title">
                Настройка: {{ nameMode }}
            </div>
        </template>
        <template #content>
            <select_temperature
                :key="'selectRightNowTemp'+String(curRoom.id)"
                :tempSelectProps="tempNow * 10"
                :nameSelectTempProps="nameSelectTempProps"
            >
            </select_temperature>
        </template>
        <template #footer>
            <button @click.prevent="saveModal" class="modal__footer_button">OK</button>
        </template>
    </modal_window>
</template>

<script>

import select_temperature from "../mode/SelectTemperature.vue";
import modal_window from "./ModalWindow.vue";

export default {
    name: "modal_right_now_temp",
    components: {modal_window, select_temperature},
    props: ['roomProps', 'tempProps', 'nameValueProps', 'classArrayProps', 'zIndexProps', 'nameSelectTempProps'],
    data() {
        return {
            curRoom: this.roomProps,
            tempNow: this.tempProps,
            beforeChangeTempNow: this.tempProps,
        }
    },
    beforeMount() {
        this.$eventBus.$on('select_temp_main', this.selectTemp);
    },
    beforeUnmount() {
        this.$eventBus.$off('select_temp_main', this.selectTemp);
    },
    methods: {
        selectTemp(selTemp) {
            this.tempNow = selTemp;
        },
        closeModal() {
            this.$eventBus.$emit('modal_select_temp', {eventName: 'close', nameValue: this.nameValueProps, temp: this.beforeChangeTempNow});
        },
        saveModal() {
            this.$eventBus.$emit('modal_select_temp', {eventName: 'save', nameValue: this.nameValueProps, temp: this.tempNow});
        },
    },

    computed: {
        itMainBlock() {
            return this.curRoom.id === 0 ? true : false;
        },
        curRoomId() {
            return this.curRoom.id;
        },
        curRoomName() {
            return this.curRoom.roomName;
        },
        nameMode() {
            if (this.nameValueProps === 'rightNowTemp') {
                return 'установка вручную температуры';
            } else if (this.nameValueProps === 'standByTemp') {
                return 'никого нет дома';
            }
        },
    },
}
</script>


<style scoped>

</style>
