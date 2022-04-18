<template>
  <div id="new-board-popup">

    <div class="backdrop" v-on:click="closePopup"></div>
    
    <div class="popup">
      <h2 class="popup-title">
        Nouveau tableau
      </h2>
      <form v-on:submit.prevent="post" class="popup-form">
        <input type="text" name="name">
        <input type="submit" value="Envoyer">
      </form>
    </div>

  </div>
</template>

<script>
export default {
  name: 'NewBoardPopup',
  methods: {
    post(e) {
      const name = e.target.elements.name.value
      const workspace = this.$store.getters.currentWorkspace()
      this.$store.dispatch('storeBoard', { name, workspace_id: workspace.id })      
    },
    closePopup() {
      this.$store.commit('closeNewBoardPopup')
    }
  },
}
</script>

<style lang="scss" scoped>
  
#new-board-popup {
  @import "../../../sass/_variables";

  z-index: 10;

  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;

  .backdrop {
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 100%;
    backdrop-filter: $blur;
  }

  .popup {
    z-index: 2;
    max-width: 360px;
    width: 100%;
    height: 150px;

    padding: 1rem;

    background-color: rgba(255, 255, 255, 0.511);

    border-radius: 10px;

    overflow: hidden;

    display: flex;
    flex-direction: column;
    align-items: center;

    position: relative;

    &-title {
      margin-bottom: 1rem;
    }

  }

}
  
</style>