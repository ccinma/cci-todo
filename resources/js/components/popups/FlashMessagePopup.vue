<template>
  <div id="messages-container" v-if="messages" >
    <p v-for="(message, index) in this.$store.state.messages" v-bind:key="'message-'+index" :class="'flash-message ' + message.severity " >
      {{ message.text }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'FlashMessagePopup',
  computed: {
    messages() {
      return this.$store.state.messages > 0
    }
  }
}
</script>

<style lang="scss">
  #messages-container {

    z-index: 10;

    position: fixed;
    top: 0;
    left: 0;
    right: 0;

    padding: 1rem;

    display: flex;
    flex-direction: column;
    align-items: center;

    .flash-message {

      padding: 0.5rem 1rem;

      &.danger {
        background: lightcoral
      }

    }

  }
</style>