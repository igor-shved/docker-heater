<template>
  <div class="content-row">
    <div class="row-exchange">
      <div class="block-text"> {{ selectArrayText }}</div>
      <div v-if="inProgress" class="loader"></div>
    </div>
    <div class="row-exchange">
      <div class="block-text row-exchange__status">{{ statusExchange }}</div>
      <a href="" class="button-block icon-trash-can" @click.prevent="deleteExchange">
      </a>
    </div>
  </div>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: "select_exchanges",
  props: ['selectExchangesProps'],
  data() {
    return {
      selectExchanges: this.selectExchangesProps,
      inProgress: false,
    }
  },
  methods: {
    deleteExchange() {
      this.$eventBus.emit('delete_select_exchange', {id: this.selectExchanges.id, exchange: this.selectExchanges});
    },
    setInProgress() {
      this.inProgress = this.selectExchanges.inProgress;
    },
   },
  computed: {
    selectArrayText() {
      let selectText = '';
      this.selectExchanges.selectArray.map((item, index) => {
        if (index === 0) {
          selectText = item.name;
        } else {
          selectText = selectText + ' => ' + item.name;
        }
      })
      return selectText;
    },
    statusExchange() {
      return this.selectExchanges.status;
    },
  },
  watch: {
    'selectExchanges.inProgress': 'setInProgress'
  },
})
</script>

