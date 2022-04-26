<template>
  <div>
    <h2>Paramètres</h2>

    <div class="button-container">
      <button class="todo-btn-secondary" v-on:click="validateLeave = true">Quitter l'espace de travail</button>
      <div class="validate">
        <button v-if="validateLeave" class="todo-btn" v-on:click="leaveWorkspace()">Valider</button>
        <button v-if="validateLeave" class="todo-btn-secondary" v-on:click="validateLeave = false">Annuler</button>
      </div>    
    </div>
    <div class="button-container">
      <button v-if="isAdmin" class="todo-btn-secondary" v-on:click="validateDelete = true">Supprimer l'espace de travail</button>
      <div class="validate">
        <button v-if="validateDelete" class="todo-btn" v-on:click="deleteWorkspace()">Valider</button>
        <button v-if="validateDelete" class="todo-btn-secondary" v-on:click="validateDelete = false">Annuler</button>
      </div>
    </div>
  </div>
</template>

<script>
  import TodoAxios from '../Axios'

  export default {
    data() {
      return {
        userId: this.$store.getters.user().id,
        workspaceMembers: this.$store.getters.currentWorkspace().members,
        isAdmin: false,

        validateLeave: false,
        validateDelete: false,
      }
    },

    mounted() {

      this.verifyAdmin();
    },

    methods: {
      verifyAdmin() {
        const loggedUser = this.workspaceMembers.find(user => user.id === this.userId)
        this.isAdmin = loggedUser.pivot.isAdmin
      },

      leaveWorkspace() {
        try {
          const index = this.workspaceMembers.findIndex(
            members => members.id === this.userId
          )
          this.workspaceMembers.splice(index, 1)
          console.log(this.workspaceMembers)

          this.$store.dispatch("reset")
          this.$router.push('/')
        }
        catch(e) {

        }
      },

      async deleteWorkspace() {
        try {
          const workspaces = this.$store.getters.workspaces()
          const currentWorkspace = this.$store.getters.currentWorkspace()
          const index = workspaces.findIndex(
            workspace => workspace.id === currentWorkspace.id
          )
          workspaces.splice(index, 1)
          this.$store.dispatch("reset")
          this.$router.push('/')
        }
        catch(e) {
          console.log("rip")
        }
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  .button-container
  {
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;

    .validate {
      display: flex;
      flex-direction: row;

      .todo-btn {
        margin-top: 0.5rem;
        background: $venetianred;
        border: $venetianred;
      }

      .todo-btn-secondary {
        margin-top: 0.5rem;
        margin-left: 1rem;
      }
    }
  }
</style>