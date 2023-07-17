import {createApp, provide} from "vue";
import store from "../store";
import axios from "axios";
import VueEventer from "vue-eventer";

const app = createApp({});
/*heater.use(router).mount('#heater'); */

app.component('heater_list', require('../components/room/HeaterList.vue').default);
//app.config.compilerOptions = {isCustomElement: (tag) => tag.startsWith('my-custom-element')};
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
        this.$consoleOutArrayObjects = function (arrayObjects) {
            arrayObjects.map(
                item => (item.map())
            );
        }
        String.prototype.hashCode = function () {
            let hash = 0;
            let len = this.length;
            if (this.length == 0) return hash;
            for (let i = 0; i < len; i++) {
                let chr = this.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash &= 0xFFFFFFFF;
            }
            let hash2 = hash >> 24;
            hash2 &= 0xFF;
            hash &= 0x00FFFFFF;

            let hash3 = hash.toString(16);

            while (hash3.length < 6)
                hash3 = '0' + hash3;

            if (hash2 != 0)
                return hash2.toString(16) + hash3;
            return hash3;
        }
        this.$setNewSetting = function (idRoomProp, nameProp, ValueProp) {
            this.SET_NEW_SETTING_ARRAY({
                idRoom: idRoomProp,
                name: nameProp,
                value: ValueProp
            });
        }
        this.$isEmpty = function (value) {
            if (value === null || value === undefined) {
                return true;
            }
            if (typeof value === 'string' || Array.isArray(value)) {
                return value.length === 0;
            }
            if (typeof value === 'object') {
                return Object.keys(value).length === 0;
            }
            return false;
        }
    }
})

app.use(store, axios).mount('#app');
