<template>
  <div :class="[classRowButtonProps]">
    <a href="" :class="classButton" @click.prevent="selectExchange">
      <div class="button-block__text">
        {{ curButton.name }}
      </div>
    </a>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "setting_exchange",
  props: ['buttonProps', 'classRowButtonProps'],
  data() {
    return {
      curButton: this.buttonProps,
      noSelect: false,
      classButton: {
        'button-block': true,
        'button-block__exchange': true,
        'button-block__select': false,
      },
    }
  },
  created() {
    this.changeClassSelect();
  },
  methods: {
    changeIsSelect(){
      this.changeClassSelect();
    },
    selectExchange() {
      this.$eventBus.$emit('select_exchange', this.curButton);
    },
    changeClassSelect(){
      if(this.curButton.isSelect){
        this.classButton['button-block__exchange'] = false;
        this.classButton['button-block__select'] = true;
      } else {
        this.classButton['button-block__exchange'] = true;
        this.classButton['button-block__select'] = false;
      }
    }
  },
  computed: {
    ...mapState({
      countExchange: 'countExchange',
      arraySelect: 'arraySelect',
    }),
  },
  watch:{
    'curButton.isSelect': 'changeIsSelect'
  },
}
</script>

