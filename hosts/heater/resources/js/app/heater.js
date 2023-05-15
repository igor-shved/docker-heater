import {createApp, provide} from "vue";
import store from "../store";
import axios from "axios";
import VueEventer from "vue-eventer";

const app = createApp({});
/*heater.use(router).mount('#heater'); */

app.component('menu_list', require('../components/MenuList.vue').default);
app.component('heater_list', require('../components/room/HeaterList.vue').default);
app.config.globalProperties.$eventBus = new VueEventer();
app.mixin({
    created() {
        this.$getArrayModesFromRoom = function (idRoom) {
            let itemArray = undefined;
            let arrayModes = [];
            let arrayModesStore = this.$store.state.arrayModesRooms;
            for (let i = 0; i < arrayModesStore.length; i++) {
                if (arrayModesStore[i].id === idRoom) {
                    itemArray = arrayModesStore[i];
                    break;
                }
            }
            if (itemArray != undefined) {
                arrayModes = itemArray.arrayModes;
            }
            return arrayModes;
        }
        this.$setIsSelectArrayModes = function (arrayModes, idMode) {
            arrayModes.forEach(item => {
                if (item.id === idMode) {
                    item.isSelect = true;
                } else {
                    item.isSelect = false;
                }
            })
        }
        this.$isEqualArrays = function (arr1, arr2) {
            if (arr1.length !== arr2.length) return false;
            for (let i = 0; i < arr1.length; i++) {
                if (typeof (arr1[i]) === 'object' && typeof (arr2[i]) === 'object') {
                    if (!this.$isEqualObjects(arr1[i], arr2[i])) {
                        return false;
                    }
                } else {
                    if (JSON.stringify(arr1[i]) !== JSON.stringify(arr2[i])) {
                        return false;
                    }
                }
            }
            return true;
        }
        this.$isEqualObjects = function isEqual(object1, object2) {
            const props1 = Object.getOwnPropertyNames(object1);
            const props2 = Object.getOwnPropertyNames(object2);
            if (props1.length !== props2.length) {
                return false;
            }
            for (let i = 0; i < props1.length; i += 1) {
                const prop = props1[i];
                const bothAreObjects = typeof (object1[prop]) === 'object' && typeof (object2[prop]) === 'object';

                if ((!bothAreObjects && (object1[prop] !== object2[prop])) ||
                    (bothAreObjects && !isEqual(object1[prop], object2[prop]))) {
                    return false;
                }
            }
            return true;
        }
        this.$copyObject = function copyObject(obj) {
            if (typeof obj !== 'object' || obj === null) {
                return obj;
            }
        }
    }
})

app.use(store, axios).mount('#app');
