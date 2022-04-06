<template>
  <div id="card">
    <p>{{ name }}</p>
    <div class="hr"/>

    <!--
    <div v-for="item in labels" class="labels">
      <p>{{ item.name }}</p>
    </div>
    -->

    <p>{{ description }}</p>
  </div>
</template>

<script>
  export default {
    props: {
      name: String,
      labels: Array,
      description: String,
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

    // .labels {
    //   width: 1rem;
    //   height: 1rem;
    //   background: red;

    //   transition: all 1s ease-out;

    //   p {
    //     color: #FFFFFF;
    //     font-size: 0.6rem;

    //     display: none;
    //   }

    //   &:hover {
    //     width: auto;
    //     background: #FFFFFF00;

        

    //     p {
    //       background: green;
    //       justify-content: center;
    //     }
    //   }
    // }

    .hr {
      margin-block: 1rem;
      background: $font-color-primary;
    }
  }
</style>
