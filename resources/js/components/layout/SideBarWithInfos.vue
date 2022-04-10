<template>
  <section class="workspace">

    <h2 class="workspace-name">
      {{ workspace.name }}
    </h2>
    <content-divider :width="'2px'" :maxLength="'90%'" :align="'center'" />

    <div>
      <h3>Tableaux</h3>
      <ul>
        <li v-for="(board, index) in workspace.boards" v-on:click="changeBoard(index)" v-bind:key="index">
          {{ board.name }}
        </li>
        <li v-if=" ! workspace.boards || workspace.boards.length == 0 ">
          Aucun tableau...
        </li>
        <li>
          <a v-on:click.prevent="openNewBoardPopup()">
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
    }
  }
}
</script>