<template>
  <div id="side-bar" :class=" isOpen ? '' : 'close' ">

    <SideBarWithInfos v-if=" !! workspace " />
    <SideBarEmpty v-if=" ! workspace" />
    <SideBarBottom v-if=" !! workspace" />

    <button id="toggle-side-bar" :class=" isOpen ? '' : 'flip' " v-on:click.prevent="toggleSidebar">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/></svg>
      </div>
    </button>
  </div>
</template>

<script>
import ContentDivider from '../UI/ContentDivider.vue'
export default {
  components: { ContentDivider },
  data() {
    return {
      workspace: this.$store.getters.currentWorkspace()
    }
  },
  computed: {
    isOpen() {
      return this.$store.getters.sidebarIsOpen()
    }
  },
  methods: {
    toggleSidebar() {
      const mutation = this.$store.getters.sidebarIsOpen() ? 'closeSidebar' : 'openSidebar'
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

    #toggle-side-bar {
      position: absolute;
      bottom: 1rem;
      left: calc(100% + 0.5rem);
      cursor: pointer;
      border-radius: 10rem;

      width: auto;

      padding: 0.75rem;

      background: $white;
      border: $white;

      transform: rotate(0deg);
      transition: transform 0.5s;

      > div {
        width: 1rem;
        height: 1rem;

        display: flex;
        justify-content: center;
        align-items: center;
      }

      svg {
        fill: $royalbluedark;
      }
    }

    .flip {
      transform: rotate(180deg)!important;
    }
  }
</style>