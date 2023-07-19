<script>
import buttons_row from "./components/ButtonsRow.vue";

export default {
  name: 'exchange',
  components: {buttons_row},
  data() {
    return {
      arrayExchange: [],
      arrayBlock: [],
      //publicPath: process.env.BASE_URL,
    }
  },
  created() {
    this.arrayExchange = [{num: 2, name: 'БА', request: ''}, {num: 3, name: 'БТ', request: ''},
      {num: 4, name: 'ЧІМК', request: ''}, {num: 5, name: 'МБ', request: ''}, {num: 6, name: 'СА', request: ''},
      {num: 7, name: 'ВХПП', request: ''}, {num: 8, name: 'АК', request: ''}, {num: 9, name: 'АП', request: ''},
      {num: 10, name: 'БХПП', request: ''}];
    let chunkArray = this.chunkArray(this.arrayExchange, 3);
    this.arrayBlock.push([{num: 1, name: 'Київ', request: ''}]);
    chunkArray.map(item => {
      this.arrayBlock.push(item);
    })
  },
  methods: {
    chunkArray(arr, size) {
      const chunkArray = [];
      for (let i = 0; i < arr.length; i += size) {
        let chunkArr = arr.slice(i, i + size);
        chunkArray.push(chunkArr);
      }
      return chunkArray;
    }
  }
}
</script>

<template>
  <div class="block-text">Виберіть бази 1С</div>
  <buttons_row v-for="(item, index) in this.arrayBlock"
               :arrButtonsProps="item"
               :indexProps="index"
               :key="'buttonsRow' + String(index)"
  >
  </buttons_row>

  <div class="block-text">Поточний план обміну:</div>
  <div class="content-row">
    <div class="button-block__text">
      СА-Київ-АП
    </div>
  </div>
  <div class="content-row">
    <div class=""
  </div>
</template>

<style>
</style>
