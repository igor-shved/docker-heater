<template>
    <div class="modal__mode_setting">
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalSetting"> <img src="/icons/settings-ex.png"/> </a>
        </div>
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalStandByTemp"> <img src="/icons/t1.png"/> </a>
        </div>
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalSchedule"> <img src="/icons/schedule-settings.png"/> </a>
        </div>
    </div>

</template>

<script>

import modal_select_temp from "../modal/ModalSelectTemp.vue";

export default {
    name: "settings_list",
    components: {modal_select_temp},
    props: {
        roomProps: {
            type: Object,
            default() {
                return {};
            }
        },
    },
    data() {
        return {
            room: this.roomProps,
        }
    },
    computed: {
    },
    methods: {
        openModalSetting(){
            this.$eventBus.$emit('open_modal_setting_relay_temp', );
        },
        openModalStandByTemp(){
            this.$eventBus.$emit('open_select_stand_by_temp');
        },
        openModalSchedule(){
            this.$eventBus.$emit('modal_select_schedule', {
                eventName: 'open',
                scheduleArray: []
            });
        },
    },
}
</script>

<style scoped>

</style>
