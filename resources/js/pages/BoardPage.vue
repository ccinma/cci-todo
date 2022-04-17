<template>
  <div v-if="board" >
    <h1 class="no-text-overflow">
      Bienvenue dans le tableau {{ board.name }}!
    </h1>

    <lane-pool :lanes="board.lanes ? board.lanes : []" />

  </div>
</template>

<script>
import LanePool from '../components/domain/board/LanePool.vue'
export default {
  components: { LanePool },
  name: 'BoardPage',
  computed: {
    board() {
      return this.$store.getters.currentBoard()
    }
  },
}
</script>

<style>
.no-text-overflow {
  white-space: nowrap;
}
</style>