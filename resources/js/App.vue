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
  computed: {
    initDone() {
      return ! this.$store.getters.initialLoading()
    }
  },
  watch: {
    initDone(newState) {
      if (newState === true) {
        if (this.$route.params.workspace && ! this.$store.getters.currentWorkspace()) {
          this.$router.push('/')
        }
        if (this.$route.params.board && ! this.$store.getters.currentBoard()) {
          this.$router.push('/workspace/' + this.$route.params.workspace)
        }
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