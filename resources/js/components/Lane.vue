<template>
  <div id="lane">
    <p class="title">{{ name }}</p>
    <div v-for="item in card">
      <Card :name="item.name"></Card>
    </div>
    <button class="todo-btn-round btn">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
      </div>
    </button>
  </div>
</template>

<script>
  import Card from './Card.vue'

  export default {
    components: {
      Card,
    },

    props: {
      name: String,
      card: Array,
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/app.scss";

  #lane {
    padding: 1rem;

    color: $font-color-secondary;
    background: $font-color-primary;

    border-radius: 1rem;

    display: flex;
    flex-direction: column;

    .title {
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .btn {
      width: auto;

      padding: 0.75rem;

      background: $font-color-secondary;
      border: $font-color-secondary;

      align-self: center;

      > div {
        width: 1rem;
        height: 1rem;

        display: flex;
        justify-content: center;
        align-items: center;
      }

      svg {
        fill: $font-color-primary;
      }
    }
  }
</style>
