<template>
    <div class="modal__schedule_container">
        <schedule_item v-for="(item, index) in scheduleArray"
                       :key="'scheduleItem' + this.currentRoom.id + index"
                       :beginPeriodProps=beginPeriodItem(item)
                       :scheduleItemProps=item
                       :scheduleArrayProps=scheduleArray
                       :indexProps=index
        >
        </schedule_item>
    </div>
</template>

<script>
import schedule_item from "./ScheduleItem.vue";
import {mapState} from "vuex";
export default {
    name: "schedule_list",
    props: ['scheduleArrayProps'],
    components: {schedule_item},
    data() {
        return {
            scheduleArray: this.scheduleArrayProps,
        }
    },
    computed: {
        ...mapState({
            currentRoom: 'currentRoom'
        }),
    },
    methods: {
        beginPeriodItem(curItem) {
            let beginPeriod = 0;
            if (this.scheduleArray.indexOf(curItem) !== 0) {
                beginPeriod = curItem.time;
            }
            return beginPeriod;
        },
    },
}
</script>

<style scoped>

</style>
