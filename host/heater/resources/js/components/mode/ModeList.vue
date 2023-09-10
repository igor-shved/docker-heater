<template>
    <div class="modal__mode_list">
        <mode_block v-for="itemMode in arrayModes"
                    :itemMode="itemMode"
                    :class="{'modal__bottom_line':itemMode.isSelect}"
                    :key="'mode'+room.id+String(itemMode.id)"
        />
    </div>

</template>

<script>
import mode_block from "./ModeBlock.vue";
export default {
    name: "modal_list",
    components: {mode_block},
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
            isOpen: false,
            arrayModes: [],
            objectSettings: {},
        }
    },
    created() {
        this.arrayModes = this.roomProps.arrayModes;
        this.$eventBus.$on('select_mode_click', this.selectModeClick);
    },
    beforeUnmount() {
        this.$eventBus.$off('select_mode_click', this.selectModeClick);
    },
    computed: {},
    methods: {
        selectModeClick(idMode) {
            this.$eventBus.$emit('modal_select_mode', idMode);

            this.arrayModes.forEach(item => {
                if (item.id === idMode) {
                    item.isSelect = true;
                } else {
                    item.isSelect = false;
                }
            });
        },
    },
}
</script>

<style scoped>

</style>
