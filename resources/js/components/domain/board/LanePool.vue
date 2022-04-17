<template>

  <ul class="lane-pool">

    <li class="lane-pool-element" v-for="lane in lanes" v-bind:key="'lane-' + lane.id">
      <lane :lane="lane" />
    </li>

    <li class="lane-pool-element new-lane">
      <div v-if="dispForm" class="new-lane-form">
        <form v-on:submit.prevent="post">
          <input type="text" name="name">
          <input type="text" name="board_id" id="board_id" hidden>
          <input type="submit" value="Envoyer">
        </form>
      </div>

      <div v-if=" ! dispForm " v-on:click.prevent="toggleForm" class="new-lane-backdrop">
        <p>
          +
        </p>
      </div>
    </li>

  </ul>
</template>

<script>
import Lane from './Lane.vue'

export default {
  components: {
    Lane
  },
  name: 'LanePool',
  props: ['lanes'],
  data() {
    return {
      dispForm: false,
    }
  },
  methods: {
    toggleForm() {
      this.dispForm = !this.dispForm
    },
    post(e) {
      const name = e.target.elements.name.value
      const board_id = e.target.elements.board_id.value
      this.$store.dispatch('storeLane', { name, board_id })  
    },
    mounted() {
      const workspaceInput = document.querySelector('#board_id')
      workspaceInput.value = this.$store.getters.currentBoard().id
    }
  }
}
</script>

<style lang="scss">

.lane-pool {

  width: 100%;
  display: flex;
  height: calc(100% - 65px);

  &-element {
    width: 300px;
    flex-shrink: 0;
    padding-inline: 0.5rem;
  }

  .new-lane {
    height: 100%;

    &-form, &-backdrop {
      background-color: rgba(0, 0, 0, 0.15);
    }

    &-backdrop {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 1rem;
      cursor: pointer;

      &:hover {
        background-color: rgba(0, 0, 0, 0.2);
      }

      p {
        font-size: 10rem;
        color: rgba(0, 0, 0, 0.15);
        cursor: pointer;
      }
    }
  }
}

</style>