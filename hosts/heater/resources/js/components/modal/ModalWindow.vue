<template>
    <!--    <div :class="classWindow" @click.prevent="closeModalMode">-->
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
    <modal_child v-if="isOpenModalSetting"
                 :roomProps="room"
                 :key="'modalSetting'+String(room.id)"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="modalStatusSetting(false)" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ room.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ room.roomName }}
            </div>
        </template>
        <template #content>
            <div class="modal__temp_relay_block">
                <label class="modal__temp_relay_element" for="nameTemp">Наименование комнаты</label>
                <input class="modal__temp_relay_element" type="text" v-model="nameRoom"/>
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
                <label class="modal__temp_relay_element" for="nameTemp">Наименование датчика температуры</label>
                <input class="modal__temp_relay_element" type="text" v-model="nameTemp"/>
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
                <label class="modal__temp_relay_element" for="nameRelay">Наименование выхода реле</label>
                <input class="modal__temp_relay_element" type="text" v-model="nameRelay"/>
            </div>
        </template>
        <template #footer>
            <button @click="saveSelectSetting" class="modal__footer_button">OK</button>
        </template>
    </modal_child>

    <modal_child v-if="isOpenModalTemp"
                 :roomProps="room"
                 :key="'modalTemp'+String(room.id)"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="modalStatusTemp(false)" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ room.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ room.roomName }}
            </div>
            <div class="modal__title">
                Настройка: никого нет дома
            </div>
        </template>
        <template #content>
            <select_temperature
                :key="'selectTempNobodyHome'"
                :tempSelectProps="tempNobodyHome"
            >
            </select_temperature>
        </template>
        <template #footer>
            <button @click="saveTempSetting" class="modal__footer_button">OK</button>
        </template>
    </modal_child>

    <modal_child v-if="isOpenModalSchedule"
                 :roomProps="room"
                 :key="'modalSchedule'+String(room.id)"
                 :classProps="classModal"
    >
        <template #buttonClose>
            <a href="" @click.prevent="modalStatusSchedule(false)" class="modal__close">X</a>
        </template>
        <template #header>
            <div class="modal__title">
                <div v-if="itMainBlock">
                    Настройки всего дома
                </div>
                <div v-else>
                    Настройки комнаты № {{ room.id }}
                </div>
            </div>
            <div class="modal__title">
                {{ room.roomName }}
            </div>
            <div class="modal__title">
                Расписание
            </div>
        </template>
        <template #content>
            <schedule_list
                :scheduleArrayProps="arrSchedule"
                :key="componentScheduleKey"
            >
            </schedule_list>
        </template>
        <template #footer>
            <div class="modal__schedule_footer">
                <div class="modal__schedule_footer_keys">
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="addScheduleItem"><img src="/icons/add-btn.png"/></a>
                        <p>Добавить</p>
                    </div>
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="removeScheduleItem"><img src="/icons/del-btn.png"/></a>
                        <p>Удалить</p>
                    </div>
                </div>
                <div class="modal__schedule_footer_keys">
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="copyScheduleRoom"><img src="/icons/copy.png"/></a>
                        <p>Копировать</p>
                    </div>
                    <div class="modal__schedule_keys_block">
                        <a href="" @click.prevent="pasteScheduleRoom"><img src="/icons/paste.png"/></a>
                        <p>Вставить</p>
                    </div>
                </div>
                <div class="modal__schedule_footer_keys">
                    <button @click.prevent="saveScheduleSetting" class="modal__footer_button">OK</button>
                </div>
            </div>
        </template>
    </modal_child>

</template>

<script>
import modal_child from "././ModalChild.vue";
import schedule_list from "../room/ScheduleList.vue";
import select_temperature from "../mode/SelectTemperature.vue";
import {mapActions, mapState} from "vuex";

export default {
    name: "modal_window",
    props: ['roomProps', 'classProps'],
    components: {schedule_list, modal_child, select_temperature},
    data() {
        return {
            room: this.roomProps,
            nameTempRoom: this.roomProps.roomsTsensorsName,
            nameRelayRoom: this.roomProps.roomsPOutputs,
            beforeNameTempRoom: this.roomProps.roomsTsensorsName,
            beforeNameRelayRoom: this.roomProps.roomsPOutputs,
            nameRelay: '',
            nameTemp: '',
            nameRoom: this.roomProps.roomName,
            beforeNameRoom: this.roomProps.roomName,
            isOpenModalMode: this.isOpenModalModeProps,
            isOpenModalSetting: false,
            isOpenModalTemp: false,
            isOpenModalSchedule: false,
            arrayTemp: this.$store.state.arrayTemp,
            arrayRelay: this.$store.state.arrayRelay,
            classWindow: this.classProps,
            arrSchedule: this.roomProps.scheduleArrRoom.map(item => ({...item})),
            tempNobodyHome: 0,
            classModal: {
                'modal__shadow_child1': true,
                'modal__modal_setting': true,
                'modal__shadow_background': true,
            },
            componentScheduleKey: 'scheduleList'
        }
    },
    created() {
        this.tempNobodyHome = this.room.standByTemp * 10;
        this.SET_CURRENT_ROOM(this.room);
        this.$eventBus.$on('select_mode_set', this.selectModeSet);
        this.$eventBus.$on('modal_open_setting', this.modalStatusSetting);
        this.$eventBus.$on('modal_open_temp', this.modalStatusTemp);
        this.$eventBus.$on('modal_open_schedule', this.modalStatusSchedule);
        this.$eventBus.$on('rerender_schedule_list', this.rerenderScheduleList);
        this.$eventBus.$on('select_temp_nobody_home', this.selectTempNoBodyHome);
    },
    mounted() {
        document.body.addEventListener("keydown", this.onKeyDown);
    },
    beforeUnmount() {
        document.body.removeEventListener("keydown", this.onKeyDown);
    },
    beforeDestroy() {
        this.$eventBus.$off('select_mode_set', this.selectModeSet);
        this.$eventBus.$off('modal_open_setting', this.modalStatusSetting);
        this.$eventBus.$off('modal_open_temp', this.modalStatusTemp);
        this.$eventBus.$off('modal_open_schedule', this.modalStatusSchedule);
        this.$eventBus.$off('select_temp_nobody_home', this.selectTempNoBodyHome);
    },
    methods: {
        ...mapActions(["SET_CURRENT_ROOM", "SET_NEW_SETTING_ARRAY", "COPY_SCHEDULE"]),
        closeModalMode() {
            this.$eventBus.$emit('close_modal_mode');
        },
        onKeyDown(event) {
            if (event.key === 'Escape') {
                this.closeModalMode();
            }
        },
        modalStatusSetting(statusModal) {
            this.isOpenModalSetting = statusModal;
        },
        modalStatusTemp(statusModal) {
            this.isOpenModalTemp = statusModal;
            this.isOpenModalTemp = statusModal;
        },
        modalStatusSchedule(statusModal) {
            if (!statusModal && !this.$isEqualArrays(this.arrSchedule, this.room.scheduleArrRoom)) {
                this.arrSchedule = this.room.scheduleArrRoom.map(item => ({...item}));
            }
            this.isOpenModalSchedule = statusModal;
        },
        selectModeSet(selMode) {
            this.selectMode = selMode;
        },
        saveSelectMode() {
            if (this.room.selectMode != null)
                this.SET_NEW_SETTING_ARRAY({
                    idRoom: this.room.id,
                    name: 'selectMode',
                    value: this.selectMode
                });
        },
        tempSensorClick(sideClick) {
            let index = this.arrayTemp.findIndex(obj => obj.value === this.nameTempRoom);
            if (sideClick === 'left') {
                if (index != 0) {
                    this.nameTempRoom = this.arrayTemp[index - 1].value;
                }
            } else {
                if (index != 15) {
                    this.nameTempRoom = this.arrayTemp[index + 1].value;
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
                }
            } else {
                if (index != 15) {
                    this.nameRelayRoom = this.arrayRelay[index + 1];
                }
            }
        },
        relayLeft() {
            this.relayClick('left')

        },
        relayRight() {
            this.relayClick('right')
        },
        saveSelectSetting() {

        },
        saveTempSetting() {
            if (this.tempNobodyHome !== this.room.standByTemp * 10) {
                this.SET_NEW_SETTING_ARRAY(
                    {
                        idRoom: this.room.id,
                        name: 'tempNobodyHome',
                        value: this.tempNobodyHome / 10
                    }
                );
            }
            this.modalStatusTemp(false);
        },
        saveScheduleSetting() {
            if (!this.$isEqualArrays(this.arrSchedule, this.room.scheduleArrRoom)) {
                this.room.scheduleArrRoom = this.arrSchedule.map(item => ({...item}));
                this.SET_NEW_SETTING_ARRAY(
                    {
                        idRoom: this.room.id,
                        name: 'scheduleArray',
                        value: this.arrSchedule.map(item => ({...item}))
                    }
                );
            }
            this.modalStatusSchedule(false);
        },
        rerenderScheduleList() {
            if (this.componentScheduleKey.includes('1')) {
                this.componentScheduleKey = this.componentScheduleKey.slice(0, this.componentScheduleKey.length - 1);
            } else {
                this.componentScheduleKey += '1';
            }
        },
        addScheduleItem() {
            if (this.arrSchedule.length >= 6) {
                return;
            }
            if (this.arrSchedule.length > 1) {
                let curTime = this.arrSchedule[this.arrSchedule.length - 2].time;
                let newItem = {...this.arrSchedule[this.arrSchedule.length - 2]};
                newItem.numStr = this.arrSchedule[this.arrSchedule.length - 1].numStr + 1;
                newItem.time = curTime + 1;
                this.arrSchedule[this.arrSchedule.length - 1].time = newItem.time;
                if (curTime <= 2358) {
                    this.arrSchedule.push(newItem);
                }

            } else if (this.arrSchedule.length === 1) {
                this.arrSchedule[0].time = 2300;
                this.arrSchedule.push({'numStr': 2, 'time': 2301, 'temp': 20, 'mode': 0});
            } else {
                this.arrSchedule.push({'numStr': 1, 'time': 0, 'temp': 20, 'mode': 0});
            }
            this.rerenderScheduleList();
        },
        removeScheduleItem() {
            if (this.arrSchedule.length <= 1)
                return;
            else
                this.arrSchedule.pop();
            this.rerenderScheduleList();
        },
        copyScheduleRoom() {
            this.COPY_SCHEDULE({
                nameRoom: this.room.roomName,
                scheduleRoom: this.arrSchedule.map(item => ({...item})),
            });
        },
        pasteScheduleRoom() {
            if (this.copySchedule.scheduleRoom.length !== 0) {
                this.arrSchedule = this.copySchedule.scheduleRoom.map(item => ({...item}));
            }
            this.rerenderScheduleList();
        },
        selectTempNoBodyHome(temp) {
            this.tempNobodyHome = temp;
        },
    },
    computed: {
        ...mapState({
            copySchedule: 'copySchedule', arrayNewSetting: 'arrayNewSetting',
        }),
        itMainBlock() {
            return this.room.id === 0;
        },
    },
}
</script>

<style scoped>

</style>
