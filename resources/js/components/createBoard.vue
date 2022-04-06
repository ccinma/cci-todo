<template>
  <div class="all_container edit">
    <input class="todo-form-text" type="text" v-model="name">
    <button class="todo-btn-round" v-on:click="send">Créer le Board</button>
  </div>
</template>

<script>
  export default {
    data() {
      return{
        name: "Nouveau Board",
      }
    },
    methods: {
      send() {
        this.$emit('create', this.name)
      }
    }
    
  }
</script>

<style lang="scss" scoped>
  .edit {
    min-height: 0!important;
    padding: 2rem!important;

    button {
      margin-top: 1rem;
    }
  }
</style>