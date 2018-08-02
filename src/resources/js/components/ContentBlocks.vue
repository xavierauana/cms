<template>
    <div class="content-block">
        <b-card no-body class="mb-1" v-if="metaContent.length">
            <b-card-header header-tag="header" class="p-1" role="tab">
                <h3 v-b-toggle.meta_content_section>Meta Content Sections</h3>
            </b-card-header>
            <b-collapse id="meta_content_section"
                        role="tabpanel">
                <b-card-body>
                    <component :is="parseContentType(block.type)"
                               v-for="(block, index) in metaContent"
                               :key="index"
                               :content="block"
                               :languages="languages"
                               :editable="editable"
                               :deleteable="deleteable"
                               v-on:updateInput="inputUpdated"
                    ></component>
                </b-card-body>
            </b-collapse>
        </b-card>

        <b-card no-body class="mb-1" v-if="notMetaContent.length">
            <b-card-header header-tag="header" class="p-1" role="tab">
                <h3 v-b-toggle.general_content_section> Content Sections</h3>
            </b-card-header>
            <b-collapse id="general_content_section"
                        visible
                        role="tabpanel">
                <b-card-body>
                    <component :is="parseContentType(block.type)"
                               v-for="(block, index) in notMetaContent"
                               :key="index"
                               :content="block"
                               :languages="languages"
                               :editable="editable"
                               :deleteable="deleteable"
                    ></component>
                </b-card-body>
            </b-collapse>
        </b-card>
        <div class="row" v-if="editable && canAdd ">
            <div class="button-container col-xs-6 col-sm-4 col-md-3 col-lg-2"
                 v-for="type in Object.keys(types)">
                <button class="btn btn-block btn-outline-primary"
                        @click.prevent="addContentBlock(type)">Add {{type | ucword}}</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {filters} from "../filters/string"
    import * as Events from "../EventNames"

    export default {
      name    : "ContentBlocks",
      props   : {
        contents  : {
          type    : Object,
          required: true
        },
        languages : {
          type    : Array,
          required: true
        },
        editable  : {
          type    : Boolean,
          required: true
        },
        deleteable: {
          type    : Boolean,
          required: true
        },
        canAdd    : {
          type    : Boolean,
          required: true
        },
        types     : {
          type    : Object,
          required: true
        },
      },
      data() {
        return {
          contentBlocks: [],
        }
      },
      computed: {
        metaContent() {
          return _.filter(this.contentBlocks, block => block.identifier.length > 0 && block.identifier.indexOf('meta_') === 0)
        },
        notMetaContent() {
          return _.filter(this.contentBlocks, block => block.identifier.length === 0 || block.identifier.indexOf('meta_') !== 0)
        },
      },
      filters : filters,
      created() {
        this.contentBlocks = this.contents ?
                             Object.keys(this.contents).map(key => Object.assign({
                                                                                   changed   : false,
                                                                                   identifier: key
                                                                                 }, this.contents[key])) :
                             []
        NotificationCenter.$on(Events.CONTENT_DELETED, this.deleteContent)
        NotificationCenter.$on(Events.CONTENT_GET_DIRTY, identifier => this.setContentBlockDirty(identifier, true))
        NotificationCenter.$on(Events.CONTENT_GET_CLEAN, identifier => this.setContentBlockDirty(identifier, false))
      },
      methods : {
        parseContentType(type) {
          return this.types[type] || null
        },
        addContentBlock(type) {
          if (Object.keys(this.types).indexOf(type) >= 0) {
            this.contentBlocks.push({
                                      type      : type,
                                      identifier: "",
                                      changed   : true
                                    })
          }
        },
        setContentBlockDirty(identifier, state) {
          const contentBlock = _.find(this.contentBlocks, {identifier: identifier})
          if (contentBlock) {
            contentBlock.changed = state
          }
        },
        deleteContent(payload) {
          this.contentBlocks = _.filter(this.contentBlocks, item => item.identifier !== payload)
        },
        inputUpdated(payload) {
          console.log("get input from content blocks")
          console.log(payload)
        }
      }
    }
</script>

<style scoped>
    .button-container {
        margin-bottom: 15px;
    }
</style>