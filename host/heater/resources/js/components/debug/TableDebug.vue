<template>
    <tbody>
    <tr v-for="(itemTr, index) in arrayDataFiles" :key="'trArrayDataFiles' + index">
        <td class="debug__element" v-for="(item, index) in itemTr" :key="'tdArrayDataFiles' + index">
            {{ item }}
        </td>
    </tr>
    </tbody>

</template>

<script>

export default {
    name: "table_debug",
    props: ['arrayDataFilesProps'],
    data() {
        return {
            arrayDataFiles: this.arrayDataFilesProps,
        }
    },
    methods: {}
}
</script>

<style scoped>

</style>
