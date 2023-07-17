import Vuex from "vuex";
import axios from "axios";

const state = {
    roomsData: [],
    isLoadDataRooms: false,
    arrayTemp: [
        {id: 0, value: '0'}, {id: 1, value: '1'}, {id: 2, value: '2'}, {id: 3, value: '3'}, {id: 4, value: '4'}, {id: 5, value: '5'}, {id: 6, value: '6'}, {id: 7, value: '7'}, {id: 8, value: '8'}, {id: 9, value: '9'}, {id: 10, value: 'a [k]'}, {id: 11,value: 'b [y]'}, {id: 12, value: 'c [a]'},{id: 13, value: 'd [b]'}, {id: 14, value: 'e [c]'}, {id: 15, value: 'f [d]'}],
    arrayRelay: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
    arrayModesRooms: [],
    arrayNewSetting: [],
    currentRoom: null,
    copySettingRoom: [],
    copySchedule: {roomId: 0, scheduleRoom: []},
};

const axios_async = async (req_url) => {
    try {
        const response = await axios.get(req_url);
        return response.data;
    } catch (error) {
        throw new Error(error);
    }
}

const actions = {
        LOAD_ROOMS_DATA({commit}, req_url) {
            // axios_async(req_url)
            //     .then(data => {
            //         commit('SET_ROOMS_DATA', data);
            //     })
            //     .catch(error => {
            //         console.log(error);
            //         commit('SET_ROOMS_DATA', []);
            //     });
            axios.get(req_url)
                .then(response => {
                    commit('SET_ROOMS_DATA', response.data);
                    commit('SET_IS_LOAD_DATA_ROOMS', true);
                    commit('SET_ARRAY_MODES_DATA', response.data);
                })
                .catch(error => {
                    console.log(error);
                    commit('SET_ROOMS_DATA', []);
                    commit('SET_IS_LOAD_DATA_ROOMS', false);
                    commit('SET_ARRAY_MODES_DATA', []);
                })
        },
        SET_NEW_SETTING_ARRAY({commit}, objSetting) {
            commit('SET_NEW_SETTING_ARRAY', objSetting);
        },
        SET_CURRENT_ROOM({commit}, curRoom) {
            commit('SET_CURRENT_ROOM', curRoom);
        },
        COPY_SCHEDULE({commit}, objSchedule) {
            commit('COPY_SCHEDULE', objSchedule);
        },
        COPY_SETTING_ROOM_TO_ARRAY({commit}, curRoom) {
            commit('COPY_SETTING_ROOM_TO_ARRAY', curRoom);
        },
    }
;

const mutations = {
    SET_ROOMS_DATA(state, roomsData) {
        state.roomsData = roomsData;
    },
    SET_IS_LOAD_DATA_ROOMS(state, value) {
        state.isLoadDataRooms = value;
    },
    SET_ARRAY_MODES_DATA(state, roomsData) {
        let arrayModes = [];
        roomsData.forEach(item => {
                arrayModes.push({id: item.id, arrayModes: item.arrayModes});
            }
        );
        state.arrayModesRooms = arrayModes;
    },
    SET_NEW_SETTING_ARRAY(state, objSetting) {
        let itemSetting = state.arrayNewSetting.find(item =>
            item.idRoom === objSetting.idRoom && item.name === objSetting.name
        );
        if (itemSetting === undefined) {
            state.arrayNewSetting.push(objSetting);
        } else {
            itemSetting.value = objSetting.value;
        }

    },
    SET_CURRENT_ROOM(state, curRoom) {
        state.currentRoom = curRoom;
    },
    COPY_SCHEDULE(state, objSchedule) {
        state.copySchedule = objSchedule;
    },
    COPY_SETTING_ROOM_TO_ARRAY(state, curRoom) {
        if (state.copySettingRoom.length !== 0 || curRoom === null) {
            state.copySettingRoom.splice(0, state.copySettingRoom.length);
        }

        let addSettingToArray = function (idRoom, nameSetting, valueSetting) {
            if (state.copySettingRoom.find(item => item.idRoom === idRoom && item.name === nameSetting)) {
                return;
            }
            state.copySettingRoom.push(
                {
                    idRoom: idRoom,
                    name: nameSetting,
                    value: valueSetting,
                }
            )
        }
        //this.$debugData('copy setting', curRoom);
        addSettingToArray(curRoom.id, 'currentRoom', curRoom);
        addSettingToArray(curRoom.id, 'currentMode', curRoom.currentMode);
        addSettingToArray(curRoom.id, 'rightNowTemp', curRoom.rightNowTemp);
        addSettingToArray(curRoom.id, 'roomsPOutputs', curRoom.roomsPOutputs);
        addSettingToArray(curRoom.id, 'roomsTsensors', curRoom.roomsTsensors);
        addSettingToArray(curRoom.id, 'standByTemp', curRoom.standByTemp);
        addSettingToArray(curRoom.id, 'scheduleSetting', curRoom.scheduleArrRoom.map(item => ({...item})));
    },
};

const getters = {
    roomsData: state => {
        return state.roomsData;
    },
    isLoadDataRooms: state => {
        return state.isLoadDataRooms;
    },
    arrayModesRooms: state => {
        return state.arrayModesRooms;
    },
    arrayNewSetting: state => {
        return state.arrayNewSetting;
    },
    currentRoom: state => {
        return state.currentRoom;
    },
    copySchedule: state => {
        return state.copySchedule;
    },
    copySettingRoom: state => {
        return state.copySettingRoom;
    },
}

export default new Vuex.Store({
    namespaced: true,
    state,
    getters,
    actions,
    mutations
});
