<script>
import button_exchange from "./ButtonExchange.vue";

export default {
  name: "button_block",
  components: {button_exchange},
  props: ['arrButtonsProps', 'indexProps'],
  data() {
    return {
      classButton: '',
      classRow: '',
    }
  },
  created() {
    if (this.indexProps === 0) {
      this.classButton = 'content-row__block-main content-row__add-exchange';
      this.classRow = 'content-row buttons-row-main';
    } else {
      this.classButton = 'content-row__block-child content-row__add-exchange';
      this.classRow = 'content-row buttons-row';
    }
  }
}
</script>

<template>
  <div :class="classRow">
    <button_exchange v-for="(item, index) in arrButtonsProps"
                     :buttonProps="item"
                     :classButtonProps="classButton"
                     :indexRowProps="indexProps"
                     :key="'button' + String(indexProps) + String(index)"
    >
    </button_exchange>
  </div>
</template>

<style scoped>

</style>