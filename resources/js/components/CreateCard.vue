<template>
  <div class="all_container edit">
    <button class="todo-btn-round btn" v-on:click="cancel">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/></svg>
      </div>
    </button>
    <input class="todo-form-text" type="text" v-model="name">
    
    <textarea cols="30" rows="10" v-model="description"></textarea>

    <button class="todo-btn-round" v-on:click="send">Créer la Card</button>
  </div>
</template>

<script>
  export default {
    data() {
      return{
        name: "Nouvelle Card",
        labels: [],
        description: "",
      }
    },
    methods: {
      cancel() {
        this.name = "";
        this.send();
      },
      send() {
        this.$emit('create', this.name, this.labels, this.description)
      },
    }
    
  }
</script>

<style lang="scss" scoped>
  .edit {
    min-height: 0!important;
    padding: 2rem!important;

    display: flex;
    flex-direction: column;
    align-items: flex-end;

    .btn {
      display: inline-block;
      width: auto;

      padding: 0.75rem;

      background: #D01114;
      border: #D01114;

      margin-top: 0;
      margin-bottom: 1rem;

      > div {
        width: 1rem;
        height: 1rem;

        display: flex;
        justify-content: center;
        align-items: center;
      }
    }

    button {
      margin-top: 1rem;
    }
  }
</style>