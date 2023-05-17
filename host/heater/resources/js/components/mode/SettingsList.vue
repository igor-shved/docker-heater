<template>
    <div class="modal__mode_setting">
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalSetting"> <img src="/icons/settings-ex.png"/> </a>
        </div>
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalTemp"> <img src="/icons/t1.png"/> </a>
        </div>
        <div class="modal__mode_block">
            <a href="" @click.prevent="openModalSchedule"> <img src="/icons/schedule-settings.png"/> </a>
        </div>
    </div>
</template>

<script>

export default {
    name: "settings_list",
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
            this.$eventBus.$emit('modal_open_setting', true);
        },
        openModalTemp(){
            this.$eventBus.$emit('modal_open_temp', true);
        },
        openModalSchedule(){
            this.$eventBus.$emit('modal_open_schedule', true);
        },
    },
}
</script>

<style scoped>

</style>
