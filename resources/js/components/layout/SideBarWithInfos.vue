<template>
  <section class="workspace">

    <h2 class="workspace-name">
      <router-link :to="'/workspace/' + workspace.id">
        {{ workspace.name }}
      </router-link>
    </h2>
    <content-divider :width="'2px'" :maxLength="'90%'" :align="'center'" />

    <div>
      <h3>Tableaux</h3>
      <ul>
        <li v-for="(board, index) in workspace.boards" v-bind:key="index" v-on:click.prevent="setCurrentBoard(board.id)">
          <router-link :to="'/workspace/'+workspace.id+'/board/'+board.id">
            {{ board.name }}
          </router-link>
        </li>
        <li v-if=" ! workspace.boards || workspace.boards.length == 0 ">
          Aucun tableau...
        </li>
        <li>
          <a v-on:click.prevent="openNewBoardPopup()" class="clickable">
            + Ajouter un tableau
          </a>
        </li>
      </ul>
    </div>
    

  </section>
</template>

<script>
export default {
  name: 'SideBarWithInfos',
  computed: {
    workspace() {
      return this.$store.state.currentWorkspace
    }
  },
  methods: {
    openNewBoardPopup() {
      this.$store.commit('openNewBoardPopup')
    },
    setCurrentBoard(id) {
      this.$store.commit('setCurrentBoard', {board_id: id})
    }
  }
}
</script>

<style lang="scss">
  .workspace {
    .clickable {
      cursor: pointer;
    }
  }
</style>