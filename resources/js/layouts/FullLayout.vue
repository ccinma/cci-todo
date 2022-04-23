<template>
  <div id="full-layout" :class="sidebarIsOpen ? 'side-bar-open' : ''">
    
    <flash-message-popup />
    <new-board-popup v-if="storeBoardPopupIsOpen" />
    <new-member-popup v-if="storeMemberPopupIsOpen" />
    <new-workspace-popup v-if="storeWorkspacePopupIsOpen" />
    <side-bar v-if=" !! workspace "/>

    <router-view class="router-view" />

  </div>
</template>

<script>

import SideBar from '../components/layout/SideBar.vue'
import FlashMessagePopup from '../components/popups/FlashMessagePopup.vue'
import NewBoardPopup from '../components/popups/NewBoardPopup.vue'
import NewMemberPopup from '../components/popups/NewMemberPopup.vue'
import NewWorkspacePopup from '../components/popups/NewWorkspacePopup.vue'


export default {
  name: 'FullLayout',
  computed: {
    workspace() {
      return this.$store.getters.currentWorkspace()
    },
    sidebarIsOpen() {
      return this.$store.getters.sidebarIsOpen()
    },
    storeBoardPopupIsOpen() {
      return this.$store.getters.newBoardPopupIsOpen()
    },
    storeMemberPopupIsOpen() {
      return this.$store.state.newMemberPopupIsOpen
    },
    storeWorkspacePopupIsOpen() {
      return this.$store.getters.newWorkspacePopupIsOpen()
    }
  },
  components: {
    SideBar,
    NewBoardPopup,
    NewMemberPopup,
    NewWorkspacePopup,
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