<template>
    <modal_window
        :key="'modalSetting'+String(roomId)"
        :classArrayProps="classArrayProps"
        :zIndexProps="this.zIndexProps"
    >
        <template #buttonClose>
            <a href="" @click.prevent="closeModal()" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ roomId }}
                </div>
            </div>
            <div class="modal__title">
                {{ roomName }}
            </div>
        </template>
        <template #content>
            <div class="modal__temp_relay_block">
                <label class="modal__temp_relay_element" for="roomName">Наименование комнаты</label>
                <input class="modal__temp_relay_element" type="text" v-model="roomName"/>
            </div>
            <div class="modal__temp_relay_block">
                <label class="modal__temp_relay_element">Датчик температуры</label>
                <div class="modal__temp_relay_setting modal__temp_relay_element">
                    <img class="modal__temp_relay_img" src="/icons/t2.png"/>
                    <p class="modal__temp_relay_text">T</p>
                    <p class="modal__temp_relay_text">{{ nameTempRoom }}</p>
                    <a href="" @click.prevent="tempSensorLeft"><img class="modal__temp_relay_img"
                                                                    src="/icons/left.png"/></a>
                    <a href="" @click.prevent="tempSensorRight"><img class="modal__temp_relay_img"
                                                                     src="/icons/right.png"/></a>
                </div>
                <label class="modal__temp_relay_element" for="tempName">Наименование датчика температуры</label>
                <input class="modal__temp_relay_element" type="text" v-model="tempName"/>
            </div>
            <div class="modal__temp_relay_block">
                <label class="modal__temp_relay_element">Номер выхода (реле)</label>
                <div class="modal__temp_relay_setting modal__temp_relay_element">
                    <img class="modal__temp_relay_img" src="/icons/relay-s.png"/>
                    <p class="modal__temp_relay_text">P</p>
                    <p class="modal__temp_relay_text">{{ nameRelayRoom }}</p>
                    <a href="" @click.prevent="relayLeft"><img class="modal__temp_relay_img" src="/icons/left.png"/></a>
                    <a href="" @click.prevent="relayRight"><img class="modal__temp_relay_img"
                                                                src="/icons/right.png"/></a>
                </div>
                <label class="modal__temp_relay_element" for="relayName">Наименование выхода реле</label>
                <input class="modal__temp_relay_element" type="text" v-model="relayName"/>
            </div>
        </template>
        <template #footer>
            <button @click="saveRelayTempSetting" class="modal__footer_button">OK</button>
        </template>
    </modal_window>
</template>

<script>
import modal_window from "./ModalWindow.vue";
import select_temperature from "../mode/SelectTemperature.vue";

export default {
    name: "modal_setting_relay_temp",
    components: {modal_window, select_temperature},
    props: ['objSettingProps', 'classArrayProps', 'zIndexProps'],
    data() {
        return {
            roomId: this.objSettingProps.roomId,
            nameTempRoom: this.$store.state.arrayTemp[this.objSettingProps.roomsTsensors].value,
            nameRelayRoom: this.objSettingProps.roomsPOutputs,
            roomsPOutputs: this.objSettingProps.roomsPOutputs,
            roomsTsensors: this.objSettingProps.roomsTsensors,
            roomName: this.objSettingProps.roomName,
            tempName: this.objSettingProps.tempName,
            relayName: this.objSettingProps.relayName,
            arrayTemp: this.$store.state.arrayTemp,
            arrayRelay: this.$store.state.arrayRelay,
        }
    },

    methods: {
        tempSensorClick(sideClick) {
            let index = this.arrayTemp.findIndex(obj => obj.value === this.nameTempRoom);
            if (sideClick === 'left') {
                if (index != 0) {
                    this.nameTempRoom = this.arrayTemp[index - 1].value;
                    this.roomsTsensors = this.arrayTemp[index - 1].id;
                }
            } else {
                if (index != 15) {
                    this.nameTempRoom = this.arrayTemp[index + 1].value;
                    this.roomsTsensors = index + 1;
                }
            }
        },
        tempSensorLeft() {
            this.tempSensorClick('left');
        },
        tempSensorRight() {
            this.tempSensorClick('right');
        },
        relayClick(sideClick) {
            let index = this.arrayRelay.findIndex(item => item === this.nameRelayRoom);
            if (sideClick === 'left') {
                if (index != 0) {
                    this.nameRelayRoom = this.arrayRelay[index - 1];
                    this.roomsPOutputs = this.nameRelayRoom;
                }
            } else {
                if (index != 15) {
                    this.nameRelayRoom = this.arrayRelay[index + 1];
                    this.roomsPOutputs = this.nameRelayRoom;
                }
            }
        },
        relayLeft() {
            this.relayClick('left')
        },
        relayRight() {
            this.relayClick('right')
        },
        closeModal() {
            let objArg = {
                eventName: 'close',
            };
            this.$eventBus.$emit('modal_select_relay_temp_name', objArg);
        },
        saveRelayTempSetting() {
            let objArg = {
                eventName: 'save',
                roomsPOutputs: this.roomsPOutputs,
                roomsTsensors: this.roomsTsensors,
                roomName: this.roomName,
                tempName: this.tempName,
                relayName: this.relayName,
            };
            this.$eventBus.$emit('modal_select_relay_temp_name', objArg)
        },
    },
    computed: {
        itMainBlock() {
            return this.roomId !== 0;
        },
    },
}
</script>


<style scoped>

</style>
