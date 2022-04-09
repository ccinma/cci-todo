<template>
  <hr :style="contentDivider" class="content-divider" />
</template>

<script>
export default {
  name: 'ContentDivider',
  props: [
    'width', 'maxLength', 'color', 'lineStyle', 'align', 'margin',
  ],
  computed: {
    contentDivider() {
      return {
        '--line-width': this.width ?? '1px',
        '--max-length': this.maxLength ?? '100%',
        '--color': this.color ?? '#032877',
        '--line-style': this.lineStyle ?? 'solid',
        '--m-left': this.align == 'right' || this.align == 'center' ? 'auto' :  '0',
        '--m-right': this.align == 'left' || this.align == 'center' ? 'auto' :  '0',
        '--margin': this.margin ?? '0.5rem',
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .content-divider {
    border: none;

    border-top: var(--line-width) var(--line-style) var(--color);

    width: var(--max-length);

    margin: var(--margin) var(--m-right) var(--margin) var(--m-left);

    opacity: 0.3;
  }
</style>