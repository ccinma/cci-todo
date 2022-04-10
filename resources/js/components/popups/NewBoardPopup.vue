<template>
  <div id="new-board-popup">
    
    <form v-on:submit.prevent="post">
      <input type="text" name="name">
      <input type="text" name="workspace_id" id="workspace_id" hidden>
      <input type="submit" value="Envoyer">
    </form>

  </div>
</template>

<script>
export default {
  name: 'NewBoardPopup',
  methods: {
    post(e) {
      const name = e.target.elements.name.value
      const workspace_id = e.target.elements.workspace_id.value
      this.$store.dispatch('storeBoard', { name, workspace_id })      
    }
  },
  mounted() {
    const workspaceInput = document.querySelector('#workspace_id')
    workspaceInput.value = this.$store.state.currentWorkspace.id
  }
}
</script>

<style lang="scss" scoped>
  
#new-board-popup {

  z-index: 10;

  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;

  max-width: 750px;

  background-color: white;

  margin: 0 auto;

}
  
</style>