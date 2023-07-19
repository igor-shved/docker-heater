<script>
export default {
  name: "setting_exchange",
  props: ['buttonProps', 'indexRowProps', 'classButtonProps'],
  methods: {
    selectExchange: {

    },
  },
}
</script>

<template>
  <div :class="[classButtonProps]">
    <a href="" class="button-block button-block__exchange" @click.prevent="selectExchange">
      <div  class="button-block__text">
        {{ buttonProps.name }}
      </div>
    </a>
  </div>
</template>
