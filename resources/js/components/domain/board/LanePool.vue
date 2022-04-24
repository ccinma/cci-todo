<template>

  <div class="lane-pool-container" v-on:click.prevent="closeForm">

    <ul id="lane-pool" class="lane-pool">
      <li class="lane-pool-element draggable-lane" v-for="lane in lanes" :data-id="lane.id" v-bind:key="'lane-' + lane.id">
        <lane :lane="lane" />
      </li>
    </ul>

    <div class="new-lane">
      <form class="new-lane-form" :class="dispForm ? 'show' : ''" v-on:submit.prevent="post" v-on:click.stop>
        <td-input-text id="lane-name-input" name="name" placeholder="Nouvelle liste" />
        <td-input-submit value="Créer" />
      </form>

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
import TdInputText from '../../UI/TdInputText.vue';
import TdInputSubmit from '../../UI/TdInputSubmit.vue';

export default {
  components: {
    Lane,
    TdInputText,
    TdInputSubmit,
  },
  props: ['lanes'],
  name: 'LanePool',
  computed: {
    dispForm() {
      return this.$store.getters.newLaneFormIsOpen()
    },
  },
  mounted() {
    this.dragNdrop(this.$store)
  },
  methods: {
    dragNdrop(store) {
      let pool = document.querySelector('#lane-pool')
      new Sortable(pool, {
        handle: '.draggable-lane',
        chosenClass: 'chosen-lane',
        animation: 200,
        delay: 100,
        delayOnTouchOnly: true,
        onEnd: function(e) {
          const sortedPool = e.to
          const newIndex = e.newIndex
          const clone = e.clone
          const lane_id = clone.dataset.id
          let previousEl = null
          let previous_id = null
          if (newIndex != 0) {
            previousEl = sortedPool.children[newIndex - 1]
          }
          if (previousEl) {
            previous_id = previousEl.dataset.id
          }
          store.dispatch('moveLane', {lane_id, previous_id: previous_id})
        }
      })
    },
    toggleForm() {
      if (this.dispForm) {
        const nameInput = document.querySelector('#lane-name-input')
        nameInput.value = null
        this.closeForm()
      } else {
        this.openForm()
        setTimeout(() => {
          const input = document.querySelector('#lane-name-input')
          input.focus()
        }, 50)
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
    border-radius: 1rem;
  }

  &-form {
    display: none;
    padding: 0.5rem;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
  }

  &-form.show {
    display: flex;
  }

  &-backdrop {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
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