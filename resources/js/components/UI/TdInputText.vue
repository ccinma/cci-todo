<template>
  <input class="td-input" @blur="blur ? blur() : ''">
</template>

<script>
export default {
  name: 'TdInputText',
  props: ['blur']
}
</script>

<style lang="scss" scoped>
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  .td-input{

    padding-inline: 1rem;
    padding-block: 0.5rem;
    
    border-radius: 5rem;
    border: 1px solid $white;

    background: $white;
    width: 100%;
  }
</style>