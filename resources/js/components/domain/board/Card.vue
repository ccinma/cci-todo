<template>
  <div class="lane-card" @click.stop>

    <div v-if="!newCard">
      <h5 class="lane-card-title">
        {{ name }}
      </h5>
      <!--
      <div v-for="item in labels" class="labels">
        <p>{{ item.name }}</p>
      </div>
      -->
      <p class="lane-card-description">
        {{ description }}
      </p>
    </div>

    <div v-if="newCard" @click.stop>
      <form class="new-card-form" @submit.prevent="postNewCard" autocomplete="off">
        <td-input-text class="new-card-input-title" type="text" name="name" placeholder="Nom de la carte..." />
        <td-input-text type="text" placeholder="Description..." name="description" />
        <td-input-submit type="submit" value="Créer" />
      </form>
    </div>

  </div>
</template>

<script>
import TdInputSubmit from '../../UI/TdInputSubmit.vue'
import TdInputText from '../../UI/TdInputText.vue'
export default {
  components: { TdInputText, TdInputSubmit },
  props: {
    name: String,
    labels: Array,
    description: String,
    newCard: {
      type: Boolean,
      default: false,
    },
    lane: Object,
    closeForm: Function
  },
  methods: {
    postNewCard() {
      const form = this.$el.querySelector('.new-card-form')
      const formData = new FormData(form)
      const name = formData.get('name')
      const description = formData.get('description')

      this.$store.dispatch('storeCard', {lane: this.lane, name, description})

      this.closeForm()
    }
  },
  mounted() {
    if (this.newCard) {
      setTimeout(() => {
        this.$el.querySelector('.new-card-input-title').focus()
      }, 100)
    }
  },
}
</script>

<style lang="scss" scoped>
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  .lane-card {

    padding: 1rem;
    
    color: $royalbluedark;

    background-color: white;
    border-radius: 0.5rem;

    border: 1px solid rgba(0, 0, 0, 0.3);

    &-title {
      font-weight: bold;
      font-size: 17px;
    }

    &-description {
      font-style: italic;
    }

    // .labels {
    //   width: 1rem;
    //   height: 1rem;
    //   background: red;

    //   transition: all 1s ease-out;

    //   p {
    //     color: #FFFFFF;
    //     font-size: 0.6rem;

    //     display: none;
    //   }

    //   &:hover {
    //     width: auto;
    //     background: #FFFFFF00;

        

    //     p {
    //       background: green;
    //       justify-content: center;
    //     }
    //   }
    // }
  }
</style>
