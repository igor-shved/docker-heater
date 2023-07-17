<template>
    <div class="debug__content">
        <div class="debug__block_row">
            <label class="debug__element" for="debugPath">Сайт для отслеживания:</label>
            <input class="debug__element debug__element_input_250" type="text" placeholder="http://"
                   v-model="debugPath"/>
            <label class="debug__element" for="debugPath">Пауза для считывания данных:</label>
            <input class="debug__element debug__element_input_30" type="text" v-model="pauseRead"/>
        </div>
        <div class="debug__block_row">
            <div class="debug__element">Список файлов для считывания</div>
            <div class="debug__block_row">
                <a class="debug__element" href="" @click.prevent="addPath"><img src="/icons/add-btn.png"/></a>
                <p class="debug__element">Добавить</p>
            </div>
            <div class="debug__block_row">
                <a class="debug__element" href="" @click.prevent="removePath"><img src="/icons/del-btn.png"/></a>
                <p class="debug__element">Удалить</p>
            </div>
            <a class="debug__block_row debug__button" href="" @click.prevent="startRead">
                Запустить вывод содержимого
            </a>
            <a class="debug__block_row debug__button" href="" @click.prevent="stopRead">
                Остановить вывод содержимого
            </a>
            <a class="debug__block_row debug__button" href="" @click.prevent="clearTable">
                Очистить таблицу
            </a>
        </div>
        <div class="debug__block_list_path">
            <div v-for="(item, index) in arrayPath" :key="index">
                <!--                <label class="debug__element" :for="'input'+ index">Путь к файлу:</label>-->
                <input class="debug__element debug__element_input_250" type="text" placeholder="Путь к файлу"
                       :id="'input' + index" :value="item" @input="updatePath($event, index)"/>
            </div>
        </div>
        <table border-spacing="1" v-if="arrayPath.length !== 0">
            <thead>
            <tr>
                <th class="debug__element" v-for="(item, index) in arrayPath" :key="'arrayPath' + index">
                    {{ item }}
                </th>
            </tr>
            </thead>
            <table_debug
                :key="'tableDebug'"
                :arrayDataFilesProps=arrayDataFiles
            >
            </table_debug>
        </table>
    </div>
</template>

<script>
import table_debug from "./TableDebug.vue";
import axios from "axios";

export default {
    name: "debug_list",
    components: {table_debug},
    data() {
        return {
            debugPath: '',
            execTimeoutRead: null,
            arrayPath: [],
            arrayRequest: [],
            arrayDataFiles: [],
            pauseRead: 15,
        }
    },
    mounted() {
        window.onerror = function (msg, url, line, col, error) {
            console.log('onerror work');
            if (error instanceof TypeError) {
                return true; // игнорировать ошибку
            }
            console.error(msg, url, line, col, error); // вывод остальных ошибок в консоль
        };
    },
    methods: {
        updatePath(event, index) {
            this.arrayPath[index] = event.target.value;
        },
        addPath() {
            if (this.arrayPath.length >= 7) {
                return;
            }
            this.arrayPath.push('');
        },
        removePath() {
            if (this.arrayPath.length !== 0) {
                this.arrayPath.pop();
            }
        },
        getArrayForRequest(arrayPath) {
            let arrayRequest = []
            arrayPath.forEach(item => {
                arrayRequest.push(this.debugPath + item);
            })
            return arrayRequest;
        },
        startReadJS() {
            let arrayRequest = this.getArrayForRequest(this.arrayPath);
            const execRead = async () => {
                let arrayData = [];
                arrayRequest.forEach(item => {
                    axios.get(item)
                        .then(response => {
                            arrayData.push(response.data);
                        })
                        .catch(err => {
                            console.log('wrong path to file: ' + item);
                            arrayData.push('');
                        })
                });
                this.arrayDataFiles.push(arrayData);
                this.execTimeoutRead = setTimeout(execRead, this.pauseRead * 1000);
            }
            execRead();
        },
        startReadLaravel() {
            let arrayRequest = this.getArrayForRequest(this.arrayPath);
            const execRead = async () => {
                let arrayData = [];
                axios.post('/api/get_data_files_debug', {data: arrayRequest})
                    .then(response => {
                        this.arrayDataFiles.push(response.data.data);
                    })
                    .catch(err => {
                        console.log('error /api/get_data_files_debug', err.response.data);
                    })
                this.execTimeoutRead = setTimeout(execRead, this.pauseRead * 1000);
            }
            execRead();

        },
        startRead() {
            if (this.arrayPath.length !== 0) {
                for (let i = this.arrayPath.length - 1; i >= 0; i--) {
                    if (this.arrayPath[i] === "") {
                        this.arrayPath.splice(i, 1);
                    }
                }
                this.startReadLaravel();
            }
        },
        stopRead() {
            clearTimeout(this.execTimeoutRead);
        },
        clearTable() {
           this.arrayDataFiles.splice(0, this.arrayDataFiles.length);
        }
    },
}
</script>

<style scoped>

</style>
