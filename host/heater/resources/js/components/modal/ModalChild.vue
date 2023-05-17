<template>
<!--    <div @click="closeModalChild" :class="classWindow">-->
    <div :class="classWindow">
        <div class="modal__container">
            <div class="modal__body" @click.stop>
                <div class="modal__header">
                    <slot name="buttonClose">
                    </slot>
                    <slot name="header">
                    </slot>
                </div>

                <div class="modal__content">
                    <slot name="content">
                    </slot>
                </div>

                <div class="modal__footer">
                    <slot name="footer">
                    </slot>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "modal_child",
    props: ['classProps'],
    data() {
        return {
            selectMode: undefined,
            classWindow: this.classProps,
          }
    },
    mounted() {
        document.body.addEventListener("keydown", this.onKeyDown);
    },
    beforeUnmount() {
        document.body.removeEventListener("keydown", this.onKeyDown);
    },
    methods: {
        closeModalChild() {
            this.$eventBus.$emit('modal_open_setting', false);
            this.$eventBus.$emit('modal_open_temp', false);
            this.$eventBus.$emit('modal_open_sсhedule', false);
        },
         onKeyDown(event){
            if (event.key === 'Escape'){
                this.closeModalChild();
            }
        },
    },
    computed: {
    },

}
</script>

<style scoped>

</style>
