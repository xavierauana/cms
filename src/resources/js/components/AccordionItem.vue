<template>
    <div class="panel" :class="itemClass">
        <div class="panel-heading" role="tab" id="headingOne">
          <h4 class="panel-title clearfix">
            <slot name="title"></slot>
            <a role="button" data-toggle="collapse" :data-parent="'#'+parentId"
               :href="'#'+itemId" aria-expanded="true"
               :aria-controls="itemId">
              <slot name="link"></slot>
            </a>
          </h4>
        </div>
        <div :id="itemId" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="headingOne">
          <div class="panel-body">
            <slot></slot>
          </div>
        </div>
    </div>
</template>

<script>
export default {
  name : "accordion-item",
  props: ['itemClass'],
  data() {
    return {
      parentId: null,
      itemId  : null
    }
  },
  created() {
    this.parentId = this.$parent.$data.componentId
    this.itemId = 'accordion_item_' + this._uid
  }
}
</script>

<style scoped>

</style>