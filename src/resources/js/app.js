/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

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


window.NotificationCenter = new Vue()

const app = new Vue({
                      el     : '#app',
                      methods: {
                        confirmDelete(e, msg) {
                          e.preventDefault()
                          if (confirm(msg)) {
                            e.target.submit()
                          }
                        }
                      }
                    });
