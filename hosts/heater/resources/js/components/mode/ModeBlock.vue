<template>
    <div class="modal__mode_block">
            <a href="" @click.prevent="selectModeClick(itemMode.id)"> <img :src="'/icons/' + itemMode.img"/> </a>
    </div>
</template>

<script>
export default {
    name: "mode_block",
    props: ['itemMode'],
    methods: {
        selectModeClick(idMode) {
            this.$eventBus.$emit('select_mode_click', idMode);
        },
    },
}
</script>

<style scoped>

</style>
