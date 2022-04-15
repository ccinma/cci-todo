<template>
  <div id="side-bar" :class=" isOpen ? '' : 'close' ">

    <SideBarWithInfos v-if=" !! workspace " />
    <SideBarEmpty v-if=" ! workspace" />
    <SideBarChangeWorkspace v-if=" !! workspace" />

    <button id="toggle-side-bar" v-on:click.prevent="toggleSidebar">
      Toggle
    </button>

  </div>
</template>

<script>
import ContentDivider from '../UI/ContentDivider.vue'
export default {
  components: { ContentDivider },
  data() {
    return {
      workspace: this.$store.getters.getCurrentWorkspace()
    }
  },
  computed: {
    isOpen() {
      return this.$store.state.sidebarIsOpen
    }
  },
  methods: {
    toggleSidebar() {
      const mutation = this.$store.state.sidebarIsOpen ? 'closeSidebar' : 'openSidebar'
      this.$store.commit(mutation)
    }
  }
}
</script>

<style lang="scss">
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  #side-bar {

    transition: transform 0.5s;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    padding-top: calc($header-height + 2rem);
    padding-bottom: 0.5rem;
    padding-inline: 1rem;

    background: rgba( 255, 255, 255, 0.5 );
    box-shadow: 0 0.5rem 2rem 0 $translucent_black;
    backdrop-filter: $blur;
    -webkit-backdrop-filter: $blur;
    border-radius: 1rem;

    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;

    margin: 0.5rem;

    width: $side-bar-width;

    &.close {
      transform: translateX(-300px);
    }

    .workspace {
      &-name {
        text-align: center;
      }
    }

    .change {

      display: flex;
      align-items: center;
      justify-content: center;

      padding-bottom: 0.5rem;

      svg {
        width: 30px;
        height: 30px;
        margin-right: 5px;
      }
    }

    #toggle-side-bar {
      position: absolute;
      bottom: 1rem;
      left: calc(100% + 0.5rem);
    }

  }
</style>