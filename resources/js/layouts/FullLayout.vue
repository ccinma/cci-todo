<template>
  <div id="full-layout" :class="sidebarIsOpen ? 'side-bar-open' : ''">
    
    <flash-message-popup />
    <new-board-popup v-if="storeBoardPopupIsOpen" />
    <side-bar v-if=" !! workspace "/>

    <router-view />



  </div>
</template>

<script>

import SideBar from '../components/layout/SideBar.vue'
import FlashMessagePopup from '../components/popups/FlashMessagePopup.vue'
import NewBoardPopup from '../components/popups/NewBoardPopup.vue'


export default {
  name: 'FullLayout',
  computed: {
    workspace() {
      return this.$store.getters.getCurrentWorkspace()
    },
    sidebarIsOpen() {
      return this.$store.state.sidebarIsOpen
    },
    storeBoardPopupIsOpen() {
      return this.$store.state.newBoardPopupIsOpen
    },
  },
  components: {
    SideBar,
    NewBoardPopup,
    FlashMessagePopup,
  },
  mounted() {

    if (this.$route.params.workspace && ! this.$store.state.currentWorkspace) {
      this.$router.push('/')
    }
    if (this.$route.params.board && ! this.$store.state.currentBoard) {
      this.$router.push('/workspace/'+this.$route.params.workspace)
    }
  },
}
</script>

<style lang="scss" scoped>
  #full-layout {
    @import "../../sass/_breakpoints.scss";
    @import "../../sass/_variables.scss";

    height: 100vh;

    position: relative;

    transition: padding 0.5s;

    padding-top: calc($mid-header-mobile + 4rem);
    @include tablet {
      padding-top: calc($mid-header-tablet + 4rem);
      padding-inline: $main-padding-inline-tablet;
    }
    @include laptop {
      padding-top: calc($mid-header-laptop + 4rem);
    }

    &.side-bar-open {
      padding-left: 316px;
    }
  }
</style>