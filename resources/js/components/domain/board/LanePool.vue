<template>

  <ul class="lane-pool">

    <li class="lane-pool-element" v-for="lane in lanes" v-bind:key="'lane-' + lane.id">
      <lane :lane="lane" />
    </li>

    <li class="lane-pool-element new-lane">
      <div class="new-lane-backdrop">
        <p>
          +
        </p>
      </div>
    </li>

  </ul>
</template>

<script>
import Lane from './Lane.vue'

export default {
  components: {
    Lane
  },
  name: 'LanePool',
  props: ['lanes'],
}
</script>

<style lang="scss">

.lane-pool {

  width: 100%;
  display: flex;
  height: calc(100% - 65px);

  &-element {
    width: 300px;
    flex-shrink: 0;
    padding-inline: 0.5rem;
  }

  .new-lane {
    height: 100%;

    &-backdrop {
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.15);
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 1rem;
      cursor: pointer;

      &:hover {
        background-color: rgba(0, 0, 0, 0.2);
      }

      p {
        font-size: 10rem;
        color: rgba(0, 0, 0, 0.15);
        cursor: pointer;
      }
    }
  }
}

</style>