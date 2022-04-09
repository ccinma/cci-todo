<template>
  <div id="side-bar">

    <SideBarWithInfos v-if=" !! workspace " v-bind:workspace="workspace" />
    <SideBarEmpty v-if=" ! workspace" />
    <SideBarChangeWorkspace v-if=" !! workspace" />

  </div>
</template>

<script>
import ContentDivider from '../UI/ContentDivider.vue'
export default {
  components: { ContentDivider },
  data() {
    return {
      isOpen: true,
      workspace: this.$store.getters.getCurrentWorkspace()
    }
  },
}
</script>

<style lang="scss">
  @import "../../../sass/_breakpoints.scss";
  @import "../../../sass/_variables.scss";
  @import "../../../sass/_colors.scss";

  #side-bar {

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    padding-top: calc($header-height + 2.5rem);
    padding-bottom: 0.5rem;

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

      p {
        
      }
    }

  }
</style>