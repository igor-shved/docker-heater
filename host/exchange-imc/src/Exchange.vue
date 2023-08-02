<script>
import buttons_row from "./components/ButtonsRow.vue";
import select_exchanges from "./components/SelectExchanges.vue";
import {mapActions, mapState} from "vuex";

export default {
  name: 'exchange',
  components: {buttons_row, select_exchanges},
  data() {
    return {
      arrayExchange: [],
      arrayBlock: [],
      arraySelect: [],
      selectExchanges: [],
      mainExchange: {id: 1, name: 'Київ', path: 'http://bases.imc.loc/imc/hs/exchange/', isSelect: false, nameExchange: 'ІМК'},
      stopExchangeState: false,
      textSelectExchange: 'Немає даних для обміну',
      visibleAddExchange: false,
      visibleOperationExchange: false,
      countSelect: 1,
      //publicPath: process.env.BASE_URL,
    }
  },
  created() {
    this.$eventBus.$on('select_exchange', this.selectExchange);
    this.$eventBus.$on('delete_select_exchange', this.deleteSelectExchange);
    this.arrayExchange = [
      {id: 2, name: 'БА', path: 'http://serverpl.poltava.loc/ba/hs/exchange/', isSelect: false, nameExchange: 'БуратАгро'},
      {id: 3, name: 'БТ', path: 'http://serverpl.poltava.loc/bt/hs/exchange/', isSelect: false, nameExchange: 'Бурат'},
      {id: 4, name: 'ЧІМК', path: 'http://serverch.chernihiv.loc/chimc/hs/exchange/', isSelect: false, nameExchange: 'ЧІМК'},
      {id: 5, name: 'МБ', path: 'http://serverch.chernihiv.loc/mb/hs/exchange/', isSelect: false, nameExchange: 'МЛИБОР'},
      {id: 6, name: 'СА', path: 'http://bases.imc.loc/sa/hs/exchange/', isSelect: false, nameExchange: 'СлобожанщинаАгро'},
      {id: 7, name: 'ВХПП', path: 'http://powervh.vhpp.loc/vhpp/hs/exchange/', isSelect: false, nameExchange: 'ВХПП'},
      {id: 8, name: 'АК', path: 'http://serverpr.priluki.loc/ak/hs/exchange/', isSelect: false, nameExchange: 'АгроКім'},
      {id: 9, name: 'АП', path: 'http://serverns.nosovka.loc/ap/hs/exchange/', isSelect: false, nameExchange: 'Агропрогрес'},
      {id: 10, name: 'БХПП', path: 'http://powerbb.bobrovica.loc/bhz/hs/exchange/', isSelect: false, nameExchange: 'БХПП'},
      {id: 11, name: 'БХЗ', path: 'http://powerbb.bobrovica.loc/bhz/hs/exchange/', isSelect: false, nameExchange: 'БХЗ'},
    ];
    let chunkArray = this.chunkArray(this.arrayExchange, 3);
    this.arrayBlock.push([this.mainExchange]);
    chunkArray.map(item => {
      this.arrayBlock.push(item);
    });
  },
  beforeUnmount() {
    this.$eventBus.$off('select_exchange', this.selectExchange);
    this.$eventBus.$off('delete_select_exchange', this.deleteSelectExchange);
  },
  watch: {
    arraySelect: {
      deep: true,
      handler(val, oldVal) {
        if (this.arraySelect.length > 1) {
          this.visibleAddExchange = true;
        } else {
          this.visibleAddExchange = false;
        }

      }
    },
    selectExchanges: {
      deep: true,
      handler(val, oldVal) {
        if (this.selectExchanges.length > 0) {
          this.visibleOperationExchange = true;
        } else {
          this.visibleOperationExchange = false;
        }

      }
    },
  },
  computed: {},
  methods: {
    ...mapActions(['CHANGE_COUNT_EXCHANGE', 'ARRAY_SELECT_EXCHANGE']),
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
    addExchange() {
      if (this.arraySelect.length <= 1) {
        return;
      }
      this.selectExchanges.push({
        id: this.countSelect,
        selectArray: [...this.arraySelect],
        status: 'виконується обмін'
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
    runExchange() {
      this.selectExchanges.forEach(itemExchange => {
        let result = this.runSelectExchange(itemExchange);
        if (result.status === 'error'){
          return;
        }
      });
    },
    runSelectExchange(itemExchange) {
      itemExchange.selectArray.forEach((itemOperation, index) => {
        let resultRequest = this.runItemOperation(itemExchange, itemOperation, index);
        if (resultRequest.status === 'error') {
          return resultRequest;
        }
      });
    },
    runItemOperation(itemExchange, itemOperation, index) {
      if (index === 0) {
        let nameOperation = 'upload';
        itemExchange.status = 'Виконується вивантаження даних на ' + itemOperation.name;
        let resultRequest = this.runRequest(nameOperation, itemOperation);
        console.log(resultRequest);
        // if (resultRequest.status === 'error') {
        //   itemExchange.status = 'Вивантаження даних виконано з помилкою, тому обмін буде зупинено. Текст помилки: ' + resultRequest.data;
        // } else {
        //   console.log(resultRequest.data);
        // }
        return resultRequest;
      } else if(index === 1) {

      }
    },
    runRequest(nameOperation, operation) {
      this.$axios.get(operation.path + nameOperation + '/' + operation.nameExchange)
          .then(function (response){
            console.log(response);
            return {
              status: 'success',
              data: response,
            }
          })
          .catch(function (error){
            console.log(error);
            return {
              status: 'error',
              data: error,
            }
          })
    },

    // this.$axios.('/api/get_data_files_debug', {data: arrayRequest})
    //     .then(response => {
    //       this.arrayDataFiles.push(response.data.data);
    //     })
    //     .catch(err => {
    //       console.log('error /api/get_data_files_debug', err.response.data);
    //     })

    stopExchange() {
    },
  }
}
</script>

<template>
  <div class="block-text">Виберіть бази 1С</div>
  <buttons_row v-for="(item, index) in this.arrayBlock"
               :key="'buttonsRow' + String(index)"
               :arrButtonsProps="item"
               :indexProps="index"
  >
  </buttons_row>

  <div class="content-row content-flex-center">
    <div class="block-text">Вибраний план обміну:</div>
  </div>
  <div class="content-row content-flex-center">
    <div class="block-text text-select-exchange">{{ textSelectExchange }}</div>
  </div>
  <div class="list-buttons">
    <div class="content-row content-flex-center">
      <div v-if="visibleAddExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__add-exchange" @click.prevent="addExchange">
          <div class="button-block__text">
            Додати обмін в список
          </div>
        </a>
      </div>
    </div>
    <div class="content-row content-flex-center">
      <div v-if="visibleOperationExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__run-exchange" @click.prevent="runExchange">
          <div class="button-block__text">
            Виконати обмін
          </div>
        </a>
      </div>
    </div>
    <div class="content-row content-flex-center">
      <div v-if="visibleOperationExchange" class="content-row__block-center">
        <a href="" class="button-block button-block__stop-exchange" @click.prevent="stopExchange">
          <div class="button-block__text">
            Зупинити обмін
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="content-row content-flex-center">
    <div v-if="visibleOperationExchange" class="block-text">Вибраний план обміну:</div>
  </div>
  <select_exchanges v-for="item in this.selectExchanges"
                    :key="'selectExchanges'+String(item.id)"
                    :selectExchangesProps=item
  >

  </select_exchanges>
</template>
