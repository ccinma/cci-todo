<template>
  <div id="choose-workspace">
    <div class="container">
      <h2>Liste des espaces de travail</h2>
      <ul v-if=" workspaces && workspaces.length > 0 ">
        <li v-for="(workspace, index) in workspaces" v-bind:key="'workspace-list-element-'+index" v-on:click.prevent="setWorkspace(workspace.id)">
          <router-link :to="'/workspace/'+workspace.id">
            {{ workspace.name }}
          </router-link>
        </li>
      </ul>
      <a v-on:click.prevent="openNewWorkspacePopup()">
        + Ajouter un espace de travail
      </a>
    </div>
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
      this.$store.dispatch('setCurrentWorkspace', {workspaceId: id})
    },
    openNewWorkspacePopup() {
      this.$store.commit('openNewWorkspacePopup')
    }
  },
  mounted() {
    this.$store.dispatch('reset')
  },
  unmounted() {
    this.$store.commit('openSidebar')
  },
  destroyed() {
    this.$store.commit('openSidebar')
  }
}
</script>

<style lang="scss">
@import "resources/sass/_breakpoints.scss";
@import "resources/sass/_variables.scss";
@import "resources/sass/_colors.scss";
  
#choose-workspace {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  .container {
    background: rgba( 255, 255, 255, 0.5 );
    box-shadow: 0 0.5rem 2rem 0 $translucent_black;
    backdrop-filter: $blur;
    -webkit-backdrop-filter: $blur;
    border-radius: 1rem;

    padding: 1rem;

    h2 {
      margin-bottom: 1rem;
    }

    ul {
      margin-bottom: 1rem;
    }

    a {
      cursor: pointer;
    }
  }
}

</style>