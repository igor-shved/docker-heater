<script>
import button_exchange from "./ButtonExchange.vue";

export default {
  name: "button_block",
  components: {button_exchange},
  props: ['arrButtonsProps', 'indexProps'],
  data() {
    return {
      classRow: '',
      classRowButton: '',
      curButton: undefined,
      buttonSelect: false,
      arrButtons: this.arrButtonsProps,
    }
  },
  created() {
    if (this.indexProps === 0) {
      this.classRow = 'content-row content-flex-center';
      this.classRowButton = 'content-row__block-center';
    } else {
      this.classRow = 'content-row content-flex-between';
      this.classRowButton = 'content-row__block-between';
    }
  },
  methods: {
  },
}
</script>

<template>
  <div :class="classRow">
    <button_exchange v-for="(item, index) in arrButtons"
                     :buttonProps="item"
                     :classRowButtonProps="classRowButton"
                     :key="'button' + String(indexProps) + String(index)"
    >
    </button_exchange>
  </div>
</template>

<style scoped>

</style>