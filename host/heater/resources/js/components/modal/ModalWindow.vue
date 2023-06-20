<template>
    <div :class="classArray" :style="{'z-index': zIndexProps}">
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
    name: "modal_window",
    props: ['classArrayProps','zIndexProps'],
    data() {
        return {
            classArray: this.classArrayProps,
        }
    },
}
</script>

<style scoped>

</style>
