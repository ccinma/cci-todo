<template>
  <div id="new-member-popup">

    <div class="backdrop" v-on:click="closePopup"></div>
    
    <div class="popup">
      <h2 class="popup-title">
        Ajouter un participant
      </h2>
      <form v-on:submit.prevent="post" class="popup-form">
        <input class="text" type="text" name="name">
        <input class="submit" type="submit" value="Envoyer">
      </form>
    </div>

  </div>
</template>

<script>
export default {
  name: 'NewMemberPopup',
  methods: {
    post(e) {
      const name = e.target.elements.name.value
      const workspace_id = e.target.elements.workspace_id.value
      this.$store.dispatch('storeMember', { name })      
    },
    closePopup() {
      this.$store.commit('closeNewMemberPopup')
    }
  },
  mounted() {
    const workspaceInput = document.querySelector('#workspace_id')
    workspaceInput.value = this.$store.state.currentWorkspace.id
  }
}
</script>

<style lang="scss" scoped>
  
#new-member-popup {
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
      .text {
        all: unset;

        padding-inline: 1rem;
        padding-block: 0.5rem;
        
        border-radius: 5rem;

        background: $white;
      }

      .submit {
        border: none;
        cursor: pointer;
        background: $cyanprocess;
        color: $white;

        padding-inline: 1rem;
        padding-block: 0.5rem;
        
        border-radius: 5rem;
      }
    }&-form {
      .text {
        all: unset;

        padding-inline: 1rem;
        padding-block: 0.5rem;
        
        border-radius: 5rem;

        background: $white;
      }

      .submit {
        border: none;
        cursor: pointer;
        background: $cyanprocess;
        color: $white;

        padding-inline: 1rem;
        padding-block: 0.5rem;
        
        border-radius: 5rem;
      }
    }

  }

}
  
</style>