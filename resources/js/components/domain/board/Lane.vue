<template>

  <div class="lane">

    <div class="lane-header">
      <h3 class="lane-header-title">{{ lane.name }}</h3>
      <div class="lane-header-menu">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div>

    <button class="todo-btn-round btn">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
      </div>
    </button>

  </div>

</template>

<script>

  export default {

    props: ['lane'],

    methods :{
    }
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  .lane {
    padding: 1rem;

    color: $white;
    background: $royalbluedark;

    border-radius: 1rem;

    display: flex;
    flex-direction: column;

    &-header {

      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;

      &-title {
        font-size: 1.2rem;
        user-select: none;
      }

      &-menu {

        display: flex;
        justify-content: center;
        gap: 2px;

        .dot {
          height: 0.5rem;
          width: 0.5rem;
          border-radius: 9999px;
          background-color: white;
          margin-top: 0.5rem;
        }
      }
    }


    .btn {
      width: auto;

      padding: 0.75rem;

      background: $white;
      border: $white;

      align-self: center;

      > div {
        width: 1rem;
        height: 1rem;

        display: flex;
        justify-content: center;
        align-items: center;
      }

      svg {
        fill: $royalbluedark;
      }
    }
  }
</style>
