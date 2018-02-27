<template>
    <ol :ref="'sortable-list' + _uid"
        class="sortable-list">
        <slot></slot>
    </ol>
</template>

<script>

    export default {
      name: "sortable-list",
      data() {
        return {
          list: null
        }
      },
      mounted() {
        Vue.nextTick(() => {
          const list = this.$refs['sortable-list' + this._uid]
        })
      }
    }
</script>

<style scoped>
    .sortable-list {
        padding: 5px;
        margin: 5px;
    }
    .sortable-list li {
        border-radius: 5px;
        border: 1px solid lightgray;
        padding: 5px;
        margin: 5px;
    }
</style>