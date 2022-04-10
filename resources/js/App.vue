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
  async mounted() {
    await this.$store.commit('init')
  }
}
</script>