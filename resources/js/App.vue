<template>
  <full-layout v-if=" ! $store.state.loading "></full-layout>
</template>

<script>
import FullLayout from './layouts/FullLayout.vue'

export default {
  name: 'App',
  components: {
    FullLayout
  },
  methods: {
    async findWorkspaces() {
      await this.$store.commit('findWorkspaces')
    }
  },
  async mounted() {
    await this.findWorkspaces()
  }
}
</script>