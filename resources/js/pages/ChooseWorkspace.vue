<template>
  <div id="choose-workspace">
    <ul v-if=" workspaces && workspaces.length > 0 ">
      <li v-for="(workspace, index) in workspaces" v-bind:key="'workspace-list-element-'+index" v-on:click.prevent="setWorkspace(workspace.id)">
        <router-link :to="'/workspace/'+workspace.id">
          {{ workspace.name }}
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'ChooseWorkspace',
  data() {
    return {
      workspaces: this.$store.state.workspaces,
      currentWorkspace: this.$store.state.currentWorkspace,
    }
  },
  methods: {
    setWorkspace(id) {
      this.$store.dispatch('setCurrentWorkspace', {workspace_id: id})
      console.log(this.$store.state.currentWorkspace)
    }
  },
  mounted() {
    this.$store.dispatch('reset')
  }
}
</script>

<style lang="scss">
  
#choose-workspace {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>