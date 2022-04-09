<template>
  <section class="workspace">

    <h2 class="workspace-name">
      {{ workspace.name }}
    </h2>
    <content-divider :width="'2px'" :maxLength="'90%'" :align="'center'" />

    <div>
      <h3>Tableaux</h3>
      <ul>
        <li v-for="(item, index) in workspace.boards" v-on:click="changeBoard(index)" v-bind:key="index">
          {{ item.name }}
        </li>
        <li v-if=" ! workspace.boards || workspace.boards.length == 0 ">
          Aucun tableau...
        </li>
      </ul>
    </div>

  </section>
</template>

<script>
export default {
  name: 'SideBarWithInfos',
  props: ['workspace'],
}
</script>