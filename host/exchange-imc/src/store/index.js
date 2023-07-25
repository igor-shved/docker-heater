import Vuex from "vuex";

const state = {
    countExchange: 0,
    arraySelect: [],
};
const actions = {
    CHANGE_COUNT_EXCHANGE({commit}, curCount) {
        commit('CHANGE_COUNT', curCount);
    },
    ARRAY_SELECT_EXCHANGE({commit}, arraySelect) {
        commit('ARRAY_SELECT', arraySelect);
    },
};

const mutations = {
    CHANGE_COUNT(state, curCount) {
        state.countExchange = curCount;
    },
    ARRAY_SELECT(state, arraySelect) {
        state.arrSelectExchange = arraySelect;
    },

};

const getters = {
    countExchange: state => {
        return state.countExchange;
    },
    arraySelect: state => {
        return state.arraySelect;
    },
}

export default new Vuex.Store({
    namespaced: true,
    state,
    getters,
    actions,
    mutations
});
