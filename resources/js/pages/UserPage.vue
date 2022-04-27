<template>
  <div id="choose-workspace">
    <div class="container">
      <h2 class="text-center">Votre profil</h2>
      <div class="profile">

        <div class="pp-form-container">
          <form @submit.prevent >
            <input type="file" name="image" id="pp-input" @change="updatePP">
          </form>
        </div>
        <div class="profile-pic-container" @click.prevent="chooseFile" title="Changer image de profil">
          <img :src="imageSrc" alt="Photo de profile">
        </div>

        <p v-if="!userNameForm" v-on:click="openFormUserName()">{{ user.name}}</p>
        <form v-if="userNameForm" @submit.prevent="changeUserName">

          <td-input-text :value="user.name" id="username-input" />
          <td-input-submit class="todo-btn-round" value="Modifier le nom" />

        </form>
      </div>

      <content-divider />

      <h3>Vos espaces de travail</h3>
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
import ContentDivider from '../components/UI/ContentDivider.vue'
import TdInputSubmit from '../components/UI/TdInputSubmit.vue'
import TdInputText from '../components/UI/TdInputText.vue'

export default {
  components: { ContentDivider, TdInputText, TdInputSubmit },
  name: 'UserPage',
  data() {
    return {
      user: this.$store.getters.user(),
      userId: this.$store.getters.user().id,
      userNameForm: false,

      workspaces: this.$store.getters.workspaces(),
      currentWorkspace: this.$store.getters.currentWorkspace(),
    }
  },
  computed: {
    imageSrc() {
      return this.user.picture ? 'images/' + this.user.picture : "/assets/images/logo.png"
    }
  },
  methods: {
    setWorkspace(id) {
      this.$store.dispatch('setCurrentWorkspace', {workspaceId: id})
    },
    openNewWorkspacePopup() {
      this.$store.commit('openNewWorkspacePopup')
    },
    openFormUserName() {
      this.userNameForm = true;
    },
    closeFormUserName() {
      this.userNameForm = false;
    },
    chooseFile() {
      this.$el.querySelector('#pp-input').click()
    },
    send() {
      this.userNameForm = false;
    },
    async updatePP() {
      const axios = this.$store.getters.axios()
      const image = this.$el.querySelector('#pp-input').files[0]
      
      const formData = new FormData()
      formData.append('image', image)
      const response = await axios.updateUserImage(this.$store.getters.user().id, formData)

      if (response.status === 200) {
        const user = this.$store.getters.user()
        user.picture = response.data.data.picture
        this.$store.state.user = user
      }
    },
    async changeUserName() {
      const axios = this.$store.getters.axios()
      const name = this.$el.querySelector('#username-input').value

      const response = await axios.updateUserInfos(this.user.id, {name})
      if (response.status === 200) {
        const user = this.$store.getters.user()
        user.name = name
        this.$store.state.user = user
        this.closeFormUserName()
      }
    },
  },
  mounted() {
    this.$store.dispatch('reset')
  },
  unmounted() {
    this.$store.commit('openSidebar')
  },
  destroyed() {
    this.$store.commit('openSidebar')
  },
}
</script>

<style lang="scss" scoped>
@import "resources/sass/_breakpoints.scss";
@import "resources/sass/_variables.scss";
@import "resources/sass/_colors.scss";
  
#choose-workspace {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  .text-center {
    text-align: center;
  }

  .pp-form-container {
    display: none;
  }

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
        background: $white;

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

      input, button{
        margin-top: 0.5rem;
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