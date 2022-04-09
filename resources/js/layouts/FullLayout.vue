<template>
  <div id=full-layout>
    
    <side-bar></side-bar>
    <router-view></router-view>

  </div>
</template>

<script>

import SideBar from '../components/layout/SideBar.vue'

export default {
  name: 'FullLayout',
  components: {
    SideBar
  },
  methods: {
    goToDashboard() {
      this.$router.push('/workspace/123')
    },
  }
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
  }
</style>