window.Vue = require('vue');
window.ContentMixin = require("./mixins/ContentBlock.js")
require('jquery-ui');

const MyPlugin = {
          install(Vue, options) {

            if (!window.NotificationCenter) window.NotificationCenter = new Vue()

            Vue.component('code-editor', require('./components/CodeEditor.vue'));
            Vue.component('sortable-list', require('./components/SortableList.vue'));
            Vue.component('content-blocks', require('./components/ContentBlocks.vue'));
            Vue.component('tabs', require('./components/Tabs.vue'));
            Vue.component('accordion-container', require('./components/AccordionContainer.vue'));
            Vue.component('accordion-item', require('./components/AccordionItem.vue'));
            Vue.component('page-children', require('./components/PageChildren.vue'));
            Vue.component('menu-block', require('./components/Menu.vue'));
            Vue.component('delete-item', require('./components/DeleteItem.vue'));

            //content blocks
            Vue.component('StringContent', require('./components/StringContentBlock'))
            Vue.component('TextContent', require('./components/TextContentBlock'))
            Vue.component('FileContent', require('./components/FileContentBlock'))
            Vue.component('NumberContent', require('./components/NumberContentBlock'))
            Vue.component('BooleanContent', require('./components/BooleanContentBlock.vue'))
            Vue.component('DatetimeContent', require('./components/DatetimeContentBlock.vue'))
            Vue.component('BaseContentBlock', require('./components/BaseContentBlock.vue'))
          }
        }

export default MyPlugin
