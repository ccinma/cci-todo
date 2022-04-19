<template>
  <div id="new-workspace-popup">

    <div class="backdrop" v-on:click="closePopup"></div>
    
    <div class="popup">
      <h2 class="popup-title">
        Nouvel espace de travail
      </h2>
      <form v-on:submit.prevent="post" class="popup-form">
        <td-input-text name="name" placeholder="Nom du workspace" />
        <td-input-submit value="Envoyer" />
      </form>
    </div>

  </div>
</template>

<script>
import TdInputSubmit from '../UI/TdInputSubmit.vue'
import TdInputText from '../UI/TdInputText.vue'
export default {
  components: { TdInputSubmit, TdInputText },
  name: 'NewWorkspacedPopup',
  methods: {
    post(e) {
      const name = e.target.elements.name.value
      this.$store.dispatch('storeWorkspace', { name })      
    },
    closePopup() {
      this.$store.commit('closeNewWorkspacePopup')
    }
  }
}
</script>

<style lang="scss" scoped>
  
#new-workspace-popup {
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

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

    &-form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.75rem;
      width: 100%;
    }
  }
}
</style>
