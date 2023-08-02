<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: "select_exchanges",
  props: ['selectExchangesProps'],
  data() {
    return {
      selectExchanges: this.selectExchangesProps,
    }
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
  methods: {
    deleteExchange() {
      this.$eventBus.emit('delete_select_exchange', {id: this.selectExchanges.id, exchange: this.selectExchanges});
    }
  }
})
</script>

<template>
  <div class="content-row">
    <div class="row-exchange">
      <div class="block-text"> {{ selectArrayText }}</div>
    </div>
    <div class="row-exchange">
      <div class="block-text row-exchange__status">{{ statusExchange }}</div>
      <a href="" class="button-block icon-trash-can" @click.prevent="deleteExchange">
      </a>
    </div>
  </div>
</template>

