<template>
  <div id="choose-workspace">
    <div class="container">
      <h2>Page de profile</h2>
      <div class="profile">
        <div class="profile-pic-container">
          <img :src="imageSrc" alt="Photo de profile">
        </div>
        <p>{{ userName}}</p>
      </div>

      <h3>Liste des espaces de travail</h3>
      <ul v-if=" workspaces && workspaces.length > 0 ">
        <li v-for="(workspace, index) in workspaces" v-bind:key="'workspace-list-element-'+index" v-on:click.prevent="setWorkspace(workspace.id)">
          <router-link :to="'/workspace/'+workspace.id">
            {{ workspace.name }}
          </router-link>
        </li>
      </ul>
      <a v-on:click.prevent="openNewWorkspacePopup()">
        + Créer un nouvel espace de travail
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserPage',
  data() {
    return {
      imageSrc: "https://img-19.commentcamarche.net/cI8qqj-finfDcmx6jMK6Vr-krEw=/1500x/smart/b829396acc244fd484c5ddcdcb2b08f3/ccmcms-commentcamarche/20494859.jpg",
      userName: this.$store.getters.user().name,

      workspaces: this.$store.getters.workspaces(),
      currentWorkspace: this.$store.getters.currentWorkspace(),
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

    .profile {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;

      margin-bottom: 1rem;
      .profile-pic-container {
        width: 10rem;
        height: 10rem;

        border-radius: 99rem;
        overflow: hidden;

        cursor: pointer;
        img {
          width: 100%;
          height: 100%;

          object-position: center center;
          object-fit: cover;
        }
      }

      p {
        font-size: 1.5rem;
        margin-top: 0.5rem;

        cursor: pointer;
      }
    }

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