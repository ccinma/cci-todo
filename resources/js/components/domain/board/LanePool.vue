<template>

  <div class="lane-pool-container" v-on:click.prevent="closeForm">

    <ul id="lane-pool" class="lane-pool">
      <li class="lane-pool-element draggable-lane" v-for="lane in lanes" v-bind:key="'lane-' + lane.id">
        <lane :lane="lane" />
      </li>
    </ul>

    <div class="new-lane" v-on:click.stop>
      <div class="new-lane-form" :class="dispForm ? 'show' : ''">
        <form v-on:submit.prevent="post">
          <input type="text" name="name" id="lane-name-input" placeholder="Nouvelle liste">
          <input type="submit" value="Envoyer">
        </form>
      </div>

      <div v-if=" ! dispForm " v-on:click.stop="toggleForm" class="new-lane-backdrop">
        <p>
          +
        </p>
      </div>
    </div>

  </div>
</template>

<script>
import Lane from './Lane.vue'
import Sortable from 'sortablejs';

export default {
  components: {
    Lane
  },
  props: ['lanes'],
  name: 'LanePool',
  computed: {
    dispForm() {
      return this.$store.getters.newLaneFormIsOpen()
    },
  },
  mounted() {
    this.dragNdrop()
  },
  methods: {
    dragNdrop() {
      let pool = document.querySelector('#lane-pool')
      new Sortable(pool, {
        handle: '.draggable-lane',
        chosenClass: 'chosen-lane',
        animation: 200,
        delay: 100,
      })
    },
    toggleForm() {
      if (this.dispForm) {
        const nameInput = document.querySelector('#lane-name-input')
        nameInput.value = null
        this.closeForm()
      } else {
        this.openForm()
      }
    },
    closeForm() {
      this.$store.commit('closeNewLaneForm')
    },
    openForm() {
      this.$store.commit('openNewLaneForm')
    },
    async post(e) {
      const name = e.target.elements.name.value
      const board_id = this.$store.getters.currentBoard().id
      await this.$store.dispatch('storeLane', { name, board_id })
      this.toggleForm()
    },
  },
}
</script>

<style lang="scss">

.lane-pool-container {
  overflow: auto;
  height: calc(100% - 65px);
  display: flex;
  width: max-content;
}

.lane-pool {
  width: 100%;
  display: flex;
  &-element {
    width: 300px;
    flex-shrink: 0;
    padding-inline: 0.5rem;
    &.draggable-lane {
      &.lane-dragging {
        opacity: 0.5;
      }
      &.lane-placeholder {
        opacity: 0.5;
      }
      &.chosen-lane {
        opacity: 0.5;
      }
    }
  }
}

.new-lane {
  @extend .lane-pool-element;
  height: 100%;
  &-form, &-backdrop {
    background-color: rgba(0, 0, 0, 0.15);
  }

  &-form {
    display: none;
  }

  &-form.show {
    display: block;
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

</style>