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
      const isOpenState = this.$store.state.sidebarIsOpen
      const isPresent = !! document.querySelector('#side-bar')
      return isOpenState && isPresent
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
}
</script>

<style lang="scss" scoped>
  #full-layout {
    @import "../../sass/_breakpoints.scss";
    @import "../../sass/_variables.scss";

    height: 100vh;

    position: relative;

    padding-block: $mid-header-mobile;
    @include tablet {
      padding-block: $mid-header-tablet;
      padding-inline: $main-padding-inline-tablet;
    }
    @include laptop {
      padding-block: $mid-header-laptop;
    }

    &.side-bar-open {
      padding-left: 300px;
    }
  }
</style>