<template>
  <input class="td-input-submit" type="submit">
</template>

<script>

</script>

<style lang="scss" scoped>
.td-input-submit {
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";
  
  border: none;
  cursor: pointer;
  background: $cyanprocess;
  color: $white;

  padding-inline: 1rem;
  padding-block: 0.5rem;
  
  border-radius: 5rem;
}
</style>