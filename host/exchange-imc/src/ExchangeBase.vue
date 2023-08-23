<template>
  <div class="block-text">Виберіть бази 1С</div>
  <buttons_row v-for="(item, index) in this.array_block"
               :key="'buttonsRow' + String(index)"
               :arrButtonsProps="item"
               :indexProps="index"
  />

  <div class="content-row content-flex-center">
    <div class="block-text">Вибраний план обміну:</div>
  </div>
  <div class="content-row content-flex-center">
    <div class="block-text text-select-exchange">{{ textSelectExchange }}</div>
  </div>
  <div class="list-buttons">
    <div class="content-row content-flex-center">
      <div v-if="visibleAddExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__add-exchange" @click.prevent="clickAddExchange">
          <div class="button-block__text">
            Додати обмін в список
          </div>
        </a>
      </div>
    </div>
    <div class="content-row content-flex-center">
      <div v-if="visibleOperationExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__run-exchange" @click.prevent="clickRunExchange">
          <div class="button-block__text">
            Виконати обмін
          </div>
        </a>
      </div>
    </div>
    <div class="content-row content-flex-center">
      <div v-if="visibleOperationExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__stop-exchange" @click.prevent="clickStopExchange">
          <div class="button-block__text">
            Зупинити обмін
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="content-row content-flex-center">
    <div v-if="visibleOperationExchange" class="check-box">
      <div>
        <input type="checkbox" id="idUpload" v-model="checkUpload">
        <label class="check-box__label" for="idUpload">вивантаження даних</label>
      </div>
      <div>
        <input type="checkbox" id="idDownload" v-model="checkDownload">
        <label class="check-box__label" for="idDownload">завантаження даних</label>
      </div>
    </div>
  </div>
  <div class="content-row content-flex-center">
    <div v-if="visibleOperationExchange" class="block-text">Вибраний план обміну:</div>
  </div>
  <select_exchanges v-for="item in selectExchanges"
                    :key="'selectExchanges'+String(item.id)"
                    :selectExchangesProps=item
  />
</template>

<script>
import buttons_row from "./components/ButtonsRow.vue";
import select_exchanges from "./components/SelectExchanges.vue";
import {mapActions} from "vuex";

export default {
  name: 'exchange_base',
  components: {buttons_row, select_exchanges},
  data() {
    return {
      active_item: {},
      arrayExchange: [],
      arrayBlock: [],
      arraySelect: [],
      selectExchanges: [],
      mainExchange: {},
      //mainExchange: {id: 1, name: 'Київ', path: 'http://i-shved.imc.loc/imc/hs/exchange/', isSelect: false, nameExchange: 'ІМК'},
      stopExchangeState: false,
      textSelectExchange: 'Немає даних для обміну',
      visibleAddExchange: false,
      visibleOperationExchange: false,
      countSelect: 1,
      operationExchangeProgress: false,
      checkUpload: true,
      checkDownload: true,
      //publicPath: process.env.BASE_URL,
    }
  },
  created() {
    this.$eventBus.$on('select_exchange', this.selectExchange);
    this.$eventBus.$on('delete_select_exchange', this.deleteSelectExchange);
    this.init_array();
  },
  beforeUnmount() {
    this.$eventBus.$off('select_exchange', this.selectExchange);
    this.$eventBus.$off('delete_select_exchange', this.deleteSelectExchange);
  },
  methods: {
    ...mapActions(['CHANGE_COUNT_EXCHANGE', 'ARRAY_SELECT_EXCHANGE']),
    async init_array() {
      this.arrayExchange = [];
      let self = this;
      this.operationProgress = true;
      await this.getRequest('https://api.imcagro.com.ua/api/v1/exchange_cluster?token=demo')
          .then(function (response) {
            if (Object.prototype.hasOwnProperty.call(response, "data")) {
              if (response.data.status === 'operation_success') {
                response.data.data.map((item, index) => {
                  if (index === 0) {
                    self.mainExchange = item;
                  } else {
                    self.arrayExchange.push(item);
                  }
                });
              } else {
                throw Error(response);
              }
            }
          })
          .catch(function (error) {
            console.log(error);
          })
      if (this.arrayExchange.length !== 0) {
        let chunkArray = this.chunkArray(this.arrayExchange, 3);
        this.arrayBlock.push([this.mainExchange]);
        chunkArray.map(item => {
          this.arrayBlock.push(item);
        });
      }
    },
    getArrayExchange() {
      // this.arrayExchange = [
      //   {id: 2, name: 'БА', path: 'http://serverpl.poltava.loc/ba/hs/exchange/', isSelect: false, nameExchange: 'БуратАгро'},
      //   {id: 3, name: 'БТ', path: 'http://serverpl.poltava.loc/bt/hs/exchange/', isSelect: false, nameExchange: 'Бурат'},
      //   {id: 4, name: 'ЧІМК', path: 'http://serverch.chernihiv.loc/chimc/hs/exchange/', isSelect: false, nameExchange: 'ЧІМК'},
      //   {id: 5, name: 'МБ', path: 'http://serverch.chernihiv.loc/mb/hs/exchange/', isSelect: false, nameExchange: 'МЛИБОР'},
      //   {id: 6, name: 'СА', path: 'http://bases.imc.loc/sa/hs/exchange/', isSelect: false, nameExchange: 'СлобожанщинаАгро'},
      //   {id: 7, name: 'ВХПП', path: 'http://powervh.vhpp.loc/vhpp/hs/exchange/', isSelect: false, nameExchange: 'ВХПП'},
      //   {id: 8, name: 'АК', path: 'http://serverpr.priluki.loc/ak/hs/exchange/', isSelect: false, nameExchange: 'АгроКім'},
      //   {id: 9, name: 'АП', path: 'http://serverns.nosovka.loc/ap/hs/exchange/', isSelect: false, nameExchange: 'Агропрогрес'},
      //   {id: 10, name: 'БХПП', path: 'http://powerbb.bobrovica.loc/bhpp/hs/exchange/', isSelect: false, nameExchange: 'БХПП'},
      //   {id: 11, name: 'БХЗ', path: 'http://powerbb.bobrovica.loc/bhz/hs/exchange/', isSelect: false, nameExchange: 'БХЗ'},
      // ];
    },

    chunkArray(arr, size) {
      const chunkArray = [];
      for (let i = 0; i < arr.length; i += size) {
        let chunkArr = arr.slice(i, i + size);
        chunkArray.push(chunkArr);
      }
      return chunkArray;
    },
    selectExchange(curButton) {
      if (this.arraySelect.length > 2 && (curButton === this.mainExchange || this.arraySelect.indexOf(curButton) === -1)) {
        return;
      }
      curButton.isSelect = !curButton.isSelect;
      if (curButton.isSelect) {
        if (this.arraySelect.indexOf(curButton) === -1) {
          this.addCurButton(curButton);
        }
      } else {
        this.removeCurButton(curButton);
      }
      this.ARRAY_SELECT_EXCHANGE(this.arraySelect);
      this.outputCurrentExchange(this.arraySelect);
    },
    addCurButton(curButton) {
      this.arraySelect.push(curButton);
      this.CHANGE_COUNT_EXCHANGE(this.arraySelect.length);
      this.changeOrderMainExchange();
    },
    removeCurButton(curButton) {
      let indexButton = this.arraySelect.indexOf(curButton);
      if (indexButton !== -1) {
        this.arraySelect.splice(indexButton, 1);
        this.changeOrderMainExchange();
        this.CHANGE_COUNT_EXCHANGE(this.arraySelect.length);
      }
    },
    changeOrderMainExchange() {
      let isMainExchange = false;
      this.arraySelect.map(item => {
        if (item === this.mainExchange) {
          isMainExchange = true;
        }
      });
      if (!isMainExchange && this.arraySelect.length > 1) {
        this.arraySelect.push(this.mainExchange);
        isMainExchange = true;
      }
      if (this.arraySelect.length > 1 && isMainExchange) {
        this.arraySelect.map((item, index) => {
          if ((item === this.mainExchange && index !== 0) || (item === this.mainExchange && index === 0 && this.arraySelect.length > 1)) {
            if (this.arraySelect.length === 3 && index !== 1) {
              this.arraySelect.splice(index, 1);
              if (this.mainExchange.isSelect === false) {
                this.mainExchange.isSelect = true;
              }
              this.arraySelect.splice(1, 0, this.mainExchange);
            }
          }
        });
      }
    },
    outputCurrentExchange(arraySelect) {
      if (arraySelect.length === 0) {
        this.textSelectExchange = 'Немає даних для обміну';
      } else {
        this.textSelectExchange = '';
        arraySelect.map((item, index) => {
          if (index === 0) {
            this.textSelectExchange = item.name;
          } else {
            this.textSelectExchange = this.textSelectExchange + ' => ' + item.name;
          }
        })
      }
    },
    clickAddExchange() {
      if (this.arraySelect.length <= 1) {
        return;
      }
      this.selectExchanges.push({
        id: this.countSelect,
        selectArray: [...this.arraySelect],
        status: '',
        inProgress: false,
      });
      this.countSelect += 1;
      this.arraySelect.map(item => {
        item.isSelect = false;
      });
      this.arraySelect.splice(0, this.arraySelect.length);
      this.outputCurrentExchange(this.arraySelect);
    },
    deleteSelectExchange(selectExchange) {
      let indexExchange = undefined;
      this.selectExchanges.map((item, index) => {
        if (selectExchange.id === item.id) {
          indexExchange = index;
        }
      })
      if (indexExchange !== undefined) {
        this.selectExchanges.splice(indexExchange, 1);
      }
    },
    clickRunExchange() {
      this.runExchange();
    },
    runExchange() {
      let arrayTasks = [];
      for (let itemExchange of this.selectExchanges) {
        for (let item of this.generateTasks(itemExchange)) {
          arrayTasks.push(item);
        }
      }
      this.runArrayTasks(arrayTasks);
    },
    async runArrayTasks(arrayTasks) {
      this.operationExchangeProgress = true;
      for (let task of arrayTasks) {
        await this.processRequest(task);
      }
      this.operationExchangeProgress = false;
    },
    generateTasks(itemExchange) {
      let index = 0;
      let arrayTasks = [];
      let lengthArray = itemExchange.selectArray.length;
      for (let baseExchange of itemExchange.selectArray) {
        if (index === 0) {
          if (baseExchange !== this.mainExchange && this.checkDownload && this.checkUpload) {
            arrayTasks.push({
              exchange: itemExchange,
              baseFrom: this.mainExchange,
              baseTo: baseExchange,
              operation: 'upload',
            });
            arrayTasks.push({
              exchange: itemExchange,
              baseFrom: baseExchange,
              baseTo: this.mainExchange,
              operation: 'download',
            });
            arrayTasks.push({
              exchange: itemExchange,
              baseFrom: baseExchange,
              baseTo: this.mainExchange,
              operation: 'upload',
            });
          } else if (this.checkUpload && !this.checkDownload && baseExchange !== this.mainExchange && (index <= lengthArray - 1)) {
            arrayTasks.push({
              exchange: itemExchange,
              baseFrom: baseExchange,
              baseTo: itemExchange.selectArray[index + 1],
              operation: 'upload',
            });
          }
        } else {
          if (baseExchange === this.mainExchange) {
            if (this.checkDownload) {
              arrayTasks.push({
                exchange: itemExchange,
                baseFrom: baseExchange,
                baseTo: itemExchange.selectArray[index - 1],
                operation: 'download',
              });
            }
          } else {
            if (this.checkUpload) {
              arrayTasks.push({
                exchange: itemExchange,
                baseFrom: itemExchange.selectArray[index - 1],
                baseTo: baseExchange,
                operation: 'upload',
              });
            }
            if (this.checkDownload) {
              arrayTasks.push({
                exchange: itemExchange,
                baseFrom: baseExchange,
                baseTo: itemExchange.selectArray[index - 1],
                operation: 'download',
              });
            }
          }
        }
        index += 1;
      }
      return arrayTasks;
    },
    async processRequest(parameters) {
      if (!this.operationExchangeProgress) {
        parameters.exchange.inProgress = false;
        return;
      }
      //await this.runProcessRequest(parameters);
      await this.runProcessRequestPython(parameters);
    },
    async runProcessRequest(parameters) {
      parameters.exchange.inProgress = true;
      let strOperationCur = '';
      let strOperationPast = '';
      let strOperationAfter = '';
      let baseName = '';
      if (parameters.operation === 'upload') {
        baseName = parameters.baseTo.name;
        strOperationCur = 'вивантаження';
        strOperationPast = 'вивантаженні';
        strOperationAfter = 'вивантаженню';
      } else if (parameters.operation === 'download') {
        baseName = parameters.baseFrom.name;
        strOperationCur = 'завантаження';
        strOperationPast = 'завантаженні';
        strOperationAfter = 'завантаженню';
      }
      parameters.exchange.status = 'Виконується ' + strOperationCur + ' даних на ' + baseName;
      let urlRequest = parameters.baseFrom.path + parameters.operation + '/' + parameters.baseTo.nameExchange;
      let result = await this.getResultGetRequest(urlRequest);
      //console.log('result', result);
      let strStatus = '';
      if (result.status === "error") {
        if (Object.prototype.hasOwnProperty.call(result, "data")) {
          if (typeof result === 'object' && Object.prototype.hasOwnProperty.call(result, "data") && Object.prototype.hasOwnProperty.call(result, "status")) {
            if (result.status === 'error') {
              let resultStr = '';
              if (Array.isArray(result.data)) {
                result.data.forEach((item, index) => {
                  if (index === 0) {
                    resultStr = item;
                  } else {
                    resultStr = resultStr + ' ' + item;
                  }
                });
              } else if (typeof result.data === "string" || (typeof result.data === "object" && result.data.constructor === String)) {
                resultStr = result.data;
              }
              strStatus = 'Виникла помилка при ' + strOperationPast + ' даних на ' + baseName + ' по причині: ' + resultStr;
            } else {
              strStatus = 'Виникла помилка при ' + strOperationPast + ' даних на ' + baseName;
            }
          }
        }
      } else if (result.status === "success") {
        strStatus = 'Операція по ' + strOperationAfter + ' даних на ' + baseName + ' виконана успішно';
      }
      parameters.exchange.status = strStatus;
      parameters.exchange.inProgress = false;
    },
    async runProcessRequestPython(parameters) {
      parameters.exchange.inProgress = true;
      let strOperationCur = '';
      let strOperationPast = '';
      let strOperationAfter = '';
      let baseName = '';
      if (parameters.operation === 'upload') {
        baseName = parameters.baseTo.name;
        strOperationCur = 'вивантаження';
        strOperationPast = 'вивантаженні';
        strOperationAfter = 'вивантаженню';
      } else if (parameters.operation === 'download') {
        baseName = parameters.baseFrom.name;
        strOperationCur = 'завантаження';
        strOperationPast = 'завантаженні';
        strOperationAfter = 'завантаженню';
      }
      parameters.exchange.status = 'Виконується ' + strOperationCur + ' даних на ' + baseName;
      let urlRequest = parameters.baseFrom.path + parameters.operation + '/' + parameters.baseTo.nameExchange;
      let response = await this.getResultPythonRequest(urlRequest);
      let result = response.data;
      let strStatus = '';
      if (result.status === "error") {
        if (Object.prototype.hasOwnProperty.call(result, "data")) {
          if (typeof result === 'object' && Object.prototype.hasOwnProperty.call(result, "data") && Object.prototype.hasOwnProperty.call(result, "status")) {
            if (result.status === 'error') {
              let resultStr = '';
              if (Array.isArray(result.data)) {
                result.data.forEach((item, index) => {
                  if (index === 0) {
                    resultStr = item;
                  } else {
                    resultStr = resultStr + ' ' + item;
                  }
                });
              } else if (typeof result.data === "string" || (typeof result.data === "object" && result.data.constructor === String)) {
                resultStr = result.data;
              }
              strStatus = 'Виникла помилка при ' + strOperationPast + ' даних на ' + baseName + ' по причині: ' + resultStr;
            } else {
              strStatus = 'Виникла помилка при ' + strOperationPast + ' даних на ' + baseName;
            }
          }
        }
      } else if (result.status === "success") {
        strStatus = 'Операція по ' + strOperationAfter + ' даних на ' + baseName + ' виконана успішно';
      }
      parameters.exchange.status = strStatus;
      parameters.exchange.inProgress = false;
    },
    async getResultPythonRequest(urlRequest) {
      return await this.postRequest({url: 'https://bot.imcagro.com.ua/web/api/exchange1c', data: urlRequest})
    },
    async getResultGetRequest(urlRequest) {
      return await this.getRequest(urlRequest)
          .then(function (response) {
            if (Object.prototype.hasOwnProperty.call(response, "data")) {
              if (Object.prototype.hasOwnProperty.call(response.data, "data")) {
                return {
                  status: 'success',
                  data: JSON.parse(response.data.data),
                }
              }
            }
            throw  Error("Where is data in response ?");
          })
          .catch(function (error) {
            if (Object.prototype.hasOwnProperty.call(error, "response")) {
              if (Object.prototype.hasOwnProperty.call(error.response, "data")) {
                try {
                  return {
                    status: 'error',
                    data: JSON.parse(error.response.data.data),
                  }
                } catch (err_try) {
                  console.log('err_try', err_try);
                  return {
                    status: 'error',
                    data: err_try,
                  }
                }
              } else {
                try {
                  return {
                    status: 'error',
                    data: 'дивіться помилку в консолі браузера - ' + error.message,
                  }
                } catch (err_try) {
                  console.log('err_try', err_try);
                  return {
                    status: 'error',
                    data: err_try,
                  }
                }
              }
            } else if (Object.prototype.hasOwnProperty.call(error, "message")) {
              try {
                return {
                  status: 'error',
                  data: 'дивіться помилку в консолі браузера - ' + error.message,
                }
              } catch (err_try) {
                console.log('err_try', err_try);
                return {
                  status: 'error',
                  data: err_try,
                }
              }
            }
          });
    },
    async getRequest(urlRequest) {
      return await this.$axios.get(urlRequest);
    },
    async postRequest(objRequest) {
      return await this.$axios.post(objRequest.url, {data: objRequest.data});
    },
    clickStopExchange() {
      this.operationExchangeProgress = false;
    },
  },
  computed: {
    array_block() {
      return this.arrayBlock.map(item => {
        if (Array.isArray(item)) {
          item.map(itemChild => {
            if (!Object.prototype.hasOwnProperty.call(itemChild, "isSelect")) {
              itemChild.isSelect = false;
            }
            if (this.selectExchanges.includes(itemChild)) {
              itemChild.isSelect = true;
            }
            return itemChild;
          });
        }

        // if (parseInt(item.id) === parseInt(this.active_item.id)) {
        //   item.isSelect = true;
        // }
        return item;
      });
    },
  },
  watch: {
    arraySelect: {
      deep: true,
      handler() {
        if (this.arraySelect.length > 1) {
          this.visibleAddExchange = true;
        } else {
          this.visibleAddExchange = false;
        }
      }
    },
    selectExchanges: {
      deep: true,
      handler() {
        if (this.selectExchanges.length > 0) {
          this.visibleOperationExchange = true;
        } else {
          this.visibleOperationExchange = false;
        }

      }
    },
  },
}
</script>
<script setup>
</script>