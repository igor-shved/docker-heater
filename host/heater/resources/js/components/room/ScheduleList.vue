<template>
    <div class="modal__schedule_container">
        <schedule_item v-for="(item, index) in scheduleArray"
                       :key="'scheduleItem'+index"
                       :beginPeriodProps=beginPeriodItem(index)
                       :scheduleItemProps=item
                       :arrayProps=scheduleArray
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
            lengthArray: this.scheduleArrayProps.length,
        }
    },
    methods: {
        beginPeriodItem(index) {
            let beginPeriod = 0;
            if (index != 0) {
                beginPeriod = this.scheduleArray[index -1].time;
            }
            return beginPeriod;
        },
    },
    computed: {
        ...mapState({
            room: 'currentRoom'
        })
    }
}
</script>

<style scoped>

</style>
