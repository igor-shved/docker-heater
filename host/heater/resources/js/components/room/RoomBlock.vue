<template>
    <div class="room">
        <div v-if="itMainBlock" class="room__clock_debug">
            <div class="room__clock_block">
                <div class="room__clock">
                    Часы
                </div>
                <div class="room__name_block">
                    {{ room.roomName }}
                </div>
            </div>
            <div class="room__debug">
                <a href="" @click.prevent="">
                    <img src="/icons/s-chat.png"/>
                </a>
            </div>
        </div>

        <div v-else class="room__temperature_name">
            <div class="room__temperature">
                <span>{{ room.roomNowTemp }}</span>
                <span>№ комнаты: {{ room.id }}</span>
                <span>P{{ room.roomsPOutputs }} - T{{ room.roomsTsensors }}</span>
            </div>
            <div class="room__name">
                {{ room.roomName }}
            </div>
        </div>

        <div class="room__block_status">
            <div class="room__temperature_block">
                <div class="room__temperature_icon">
                    <a href="" @click.prevent="debug">
                        <img src="/icons/t1.png"/>
                    </a>
                </div>
                <div class="room__temperature_value">
                    {{ room.currentModeTextArray }}
                </div>
            </div>
            <div class="room__state">
                <img v-if="onRelay" src="/icons/h.png"/>
            </div>
            <div class="room__settings">
                <a href="" @click.prevent="openModalAllSetting">
                    <img :src="imgSettings"/>
                </a>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "room_block",
    components: {},
    props: {
        roomProps: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            arrayModes: [],
            room: this.roomProps,
        }
    },
    created() {
        this.arrayModes = this.$getArrayModesFromRoom(this.room.id);

    },
    computed: {
        itMainBlock() {
            return this.room.id === 0;
        },
        imgSettings() {
            return '/icons/' + this.arrayModes.filter(item => {
                return item.id === this.room.currentMode;
            })[0]['img'];
        },
        onRelay() {
            return this.room.currentStatusRelay === 1;
        }
    },
    methods: {
        openModalAllSetting() {
            this.$eventBus.$emit('modal_all_setting', 'open', this.room);
        }
    },
}
</script>
