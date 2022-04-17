<template>
  <div id="full-layout" :class="sidebarIsOpen ? 'side-bar-open' : ''">
    
    <flash-message-popup />
    <new-board-popup v-if="storeBoardPopupIsOpen" />
    <new-workspace-popup v-if="storeWorkspacePopupIsOpen" />
    <side-bar v-if=" !! workspace "/>

    <router-view class="router-view" />

  </div>
</template>

<script>

import SideBar from '../components/layout/SideBar.vue'
import FlashMessagePopup from '../components/popups/FlashMessagePopup.vue'
import NewBoardPopup from '../components/popups/NewBoardPopup.vue'
import NewWorkspacePopup from '../components/popups/NewWorkspacePopup.vue'


export default {
  name: 'FullLayout',
  computed: {
    workspace() {
      return this.$store.getters.currentWorkspace()
    },
    sidebarIsOpen() {
      return this.$store.state.sidebarIsOpen
    },
    storeBoardPopupIsOpen() {
      return this.$store.state.newBoardPopupIsOpen
    },
    storeWorkspacePopupIsOpen() {
      return this.$store.state.newWorkspacePopupIsOpen
    }
  },
  components: {
    SideBar,
    NewBoardPopup,
    NewWorkspacePopup,
    FlashMessagePopup,
  },
  mounted() {
    if (this.$route.params.workspace && ! this.$store.state.currentWorkspace) {
      this.$router.push('/')
    }
    if (this.$route.params.board && ! this.$store.state.currentBoard) {
      this.$router.push('/workspace/' + this.$route.params.workspace)
    }
  }
}
</script>

<style lang="scss" scoped>
  #full-layout {
    @import "../../sass/_breakpoints.scss";
    @import "../../sass/_variables.scss";

    height: 100vh;

    position: relative;

    transition: padding 0.5s;

    padding-inline: $main-padding-inline-mobile;
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

    .router-view {
      overflow: auto;
      height: 100%;
    }
  }
</style>