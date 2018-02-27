<template>
    <div class="panel-group" :ref="componentId"
         :id="componentId" role="tablist"
         aria-multiselectable="true">
        <slot></slot>
    </div>
</template>

<script>
export default {
  name: "accordion-container",
  data() {
    return {
      componentId: 'accordion_container_' + this._uid
    }
  }
}
</script>

<style scoped>

</style>