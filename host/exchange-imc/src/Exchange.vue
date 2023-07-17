<script>
import button_exchange from "./components/ButtonExchange.vue";

export default {
  name: 'exchange',
  components: {button_exchange},
  data() {
    return {
      arrayExchange: [],
    }
  },
  created() {
    this.arrayExchange = ['Київ', 'БА', 'БТ', 'ЧІМК', 'МБ', 'СА', 'ВХПП', 'АК', 'АП', 'БХПП']
  }
}
</script>

<template>
  <div class="content">
    <h1 class="block-text">Настройка обмена между базами 1С должна работать</h1>
    <button_exchange></button_exchange>
  </div>
</template>

<style>
</style>
