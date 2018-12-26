//import Vue from "vue";
//window.ContentMixin = require("./mixins/ContentBlock.js")
require('jquery-ui');

window.Vue = window.Vue || require('vue')


import vBootstrap from "bootstrap-vue"
import CodeEditor from "./components/CodeEditor"
import SortableList from "./components/SortableList"
import ContentBlocks from "./components/ContentBlocks"
import PageChildren from "./components/PageChildren"
import BaseDateTimeInput from "./components/BaseDateTimeInput"
import AccordionContainer from "./components/AccordionContainer"
import AccordionItem from "./components/AccordionItem"
import Menu from "./components/Menu"
import ProgressBar from "./components/ProgressBar"
import DeleteItem from "./components/DeleteItem"
//content blocks
import BaseContentBlock from "./components/BaseContentBlock"
import StringContentBlock from "./components/StringContentBlock"
import TextContentBlock from "./components/TextContentBlock"
import PlainTextContentBlock from "./components/PlainTextContentBlock"
import FileContentBlock from "./components/FileContentBlock"
import NumberContentBlock from "./components/NumberContentBlock"
import BooleanContentBlock from "./components/BooleanContentBlock"
import DatetimeContentBlock from "./components/DatetimeContentBlock"
import DateContentBlock from "./components/DateContentBlock"
import EncodedVideoContentBlock from "./components/EncodedVideoContentBlock"

Vue.use(vBootstrap)


export default {
  install(Vue, options) {
    if (!window.NotificationCenter) window.NotificationCenter = new Vue()
    Vue.component('code-editor', CodeEditor);
    Vue.component('sortable-list', SortableList);
    Vue.component('content-blocks', ContentBlocks);
    Vue.component('accordion-container', AccordionContainer);
    Vue.component('accordion-item', AccordionItem);
    Vue.component('page-children', PageChildren);
    Vue.component('menu-block', Menu);
    Vue.component('delete-item', DeleteItem);
    Vue.component('progress-bar', ProgressBar);
    Vue.component('base-datetime', BaseDateTimeInput);

    //content blocks
    Vue.component('StringContent', StringContentBlock)
    Vue.component('TextContent', TextContentBlock)
    Vue.component('PlainTextContent', PlainTextContentBlock)
    Vue.component('FileContent', FileContentBlock)
    Vue.component('NumberContent', NumberContentBlock)
    Vue.component('BooleanContent', BooleanContentBlock)
    Vue.component('DatetimeContent', DatetimeContentBlock)
    Vue.component('DateContent', DateContentBlock)
    Vue.component('BaseContentBlock', BaseContentBlock)
    Vue.component('EncodedVideoContentBlock', EncodedVideoContentBlock)
  }
}