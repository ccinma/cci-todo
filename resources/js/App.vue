<template>
  <full-layout v-if=" ! $store.state.initialLoading "></full-layout>
</template>

<script>
import FullLayout from './layouts/FullLayout.vue'

export default {
  name: 'App',
  components: {
    FullLayout
  },
  // data() {
  //   return {
  //     initDone: this.$store.state.initialLoading
  //   }
  // },
  computed: {
    initDone() {
      return ! this.$store.getters.initialLoading()
    }
  },
  watch: {
    initDone(newState) {
      if (newState === true) {
        console.log(this.$store.getters.currentWorkspace())
      }
    }
  },
  async mounted() {
    const routeParams = this.$route.params
    const params = {
      workspaceId: routeParams.workspace,
      boardId: routeParams.board
    }
    await this.$store.dispatch('init', params)

  }
}
</script>