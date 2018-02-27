<template>
    <div :id="'tab_component_'+_uid">
        <ul class="nav nav-tabs" v-if="tabs.length > 1">
          <li v-for="tab  in tabs" role="presentation"
              :class="{' active':activeTab===tab.id}"><a
                  :href="'#'+tab.id" v-text="tab.label"
                  @click.prevent="changeTab(tab)"></a></li>
        </ul>
        <div class="tab-content">
            <div v-for="tab in tabs" :id="tab.id" class="tab-pane fade"
                 :class="{' active in':activeTab===tab.id}">
                <slot :name="tab.id"></slot>
            </div>
        </div>
    </div>

</template>

<script>
export default {
  name   : "tab",
  props  : ['tabs'],
  data() {
    return {
      activeTab: null
    }
  },
  created() {
    this.activeTab = this.tabs[0].id
  },
  methods: {
    changeTab(tab) {
      this.activeTab = tab.id
    }
  }

}
</script>

<style scoped>

</style>