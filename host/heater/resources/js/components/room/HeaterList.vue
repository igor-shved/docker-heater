<template>
    <template v-if="isLoadDataRooms">
        <room_block v-for="room in roomsData"
                    :roomProps="room"
                    :key="'room'+ String(room.id)"
        />
    </template>
    <div v-else class="loading_status">
        <span>Loading data</span>
    </div>
    <modal_all_setting v-if="isOpenModalAllSetting"
                       :roomProps="curRoom"
                       :zIndexProps = 1001
    />
</template>

<script>
import room_block from "./RoomBlock.vue";
import modal_all_setting from "../modal/ModalAllSetting.vue";
import {mapState, mapActions} from "vuex";

export default {
    name: "heater_list",
    components: {room_block, modal_all_setting},
    data() {
        return {
            curRoom: null,
            isOpenModalAllSetting: false,
        }
    },
    beforeMount() {
        document.body.addEventListener("keydown", this.onKeyDown);
        this.$eventBus.$on("modal_all_setting", this.modalAllSetting);
        this.$eventBus.$on("update_state_rooms", this.updateStateRooms);
        //setTimeout(this.loadDataRooms, 3000);
        this.loadDataRooms();
    },
    beforeUnmount() {
        document.body.removeEventListener("keydown", this.onKeyDown);
        this.$eventBus.$off("modal_all_setting", this.modalAllSetting);
        this.$eventBus.$off("update_state_rooms", this.updateStateRooms);
    },
    methods: {
        ...mapActions(['LOAD_ROOMS_DATA']),
        onKeyDown(event) {
            if (event.key === 'Escape') {
                this.closeModalAllSetting(this.curRoom);
            }
        },
        modalAllSetting(eventName, curRoom) {
            if (eventName === "open"){
                this.openModalAllSetting(curRoom);
            } else if (eventName === "close"){
                this.closeModalAllSetting(curRoom);
            }
        },
        openModalAllSetting(curRoom) {
            this.curRoom = curRoom;
            this.isOpenModalAllSetting = true;
            document.body.classList.add('body__overflow_y_hidden');
        },
        closeModalAllSetting(curRoom) {
            this.curRoom = curRoom;
            this.isOpenModalAllSetting = false;
            document.body.classList.remove('body__Overflow_y_hidden');
        },
        async loadDataRooms(){
            await this.LOAD_ROOMS_DATA('/api/get_rooms_data');
        },
        updateStateRooms(){

        },
    },

    computed: {
        ...mapState({
            roomsData: 'roomsData',
            isLoadDataRooms: 'isLoadDataRooms',
        }),
        // // input -> title set ID //from Loik Max
        // some_prop : {
        //     get : function() {
        //         return this.obk.title;
        //     },
        //     set : function(old_val,new_val) {
        //        /// this.cur_id = parseInt(newVal);
        //     }
        // },
    },
}
</script>

<style scoped>
</style>
