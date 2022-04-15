<template>
  <div>
    <h1>
      Bienvenue dans un tableau {{ board.name }}!
    </h1>
    <p>
      C'est ici le coeur du projet avec du drag and drop et tout !
    </p>
  </div>
</template>

<script>
export default {
  name: 'BoardPage',
  computed: {
    board() {
      return this.$store.state.currentBoard
    }
  }
}
</script>