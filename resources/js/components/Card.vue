<template>
  <div id="card">
    <p>{{ name }}</p>
  </div>
</template>

<script>
  export default {
    props: {
      name: String,
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/app.scss";

  #card {
    padding: 1rem;
    margin-bottom: 1rem;

    color: $font-color-primary;
    background: $font-color-secondary;

    border-radius: 1rem;
  }
</style>
