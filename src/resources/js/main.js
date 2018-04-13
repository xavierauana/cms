//import Vue from "vue";
//window.ContentMixin = require("./mixins/ContentBlock.js")
require('jquery-ui');

import CodeEditor from "./components/CodeEditor"
import SortableList from "./components/SortableList"
import ContentBlocks from "./components/ContentBlocks"
import PageChildren from "./components/PageChildren"
import Tabs from "./components/Tabs"
import AccordionContainer from "./components/AccordionContainer"
import AccordionItem from "./components/AccordionItem"
import Menu from "./components/Menu"
import DeleteItem from "./components/DeleteItem"
//content blocks
import BaseContentBlock from "./components/BaseContentBlock"
import StringContentBlock from "./components/StringContentBlock"
import TextContentBlock from "./components/TextContentBlock"
import FileContentBlock from "./components/FileContentBlock"
import NumberContentBlock from "./components/NumberContentBlock"
import BooleanContentBlock from "./components/BooleanContentBlock"
import DatetimeContentBlock from "./components/DatetimeContentBlock"

export default {
  install(Vue, options) {

    Vue.mixin({
                created() {
                  console.log("plugin created in mixin")
                }
              })
    if (!window.NotificationCenter) window.NotificationCenter = new Vue()

    Vue.component('code-editor', CodeEditor);
    Vue.component('sortable-list', SortableList);
    Vue.component('content-blocks', ContentBlocks);
    Vue.component('tabs', Tabs);
    Vue.component('accordion-container', AccordionContainer);
    Vue.component('accordion-item', AccordionItem);
    Vue.component('page-children', PageChildren);
    Vue.component('menu-block', Menu);
    Vue.component('delete-item', DeleteItem);

    //content blocks
    Vue.component('StringContent', StringContentBlock)
    Vue.component('TextContent', TextContentBlock)
    Vue.component('FileContent', FileContentBlock)
    Vue.component('NumberContent', NumberContentBlock)
    Vue.component('BooleanContent', BooleanContentBlock)
    Vue.component('DatetimeContent', DatetimeContentBlock)
    Vue.component('BaseContentBlock', BaseContentBlock)
  }
}