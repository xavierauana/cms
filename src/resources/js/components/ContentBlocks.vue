<template>
    <div class="content-block">
        <div class="panel panel-default">
            <div class="panel-body">
            <component :is="parseContentType(block.type)"
                       v-for="(block, index) of contentBlocks"
                       :key="index"
                       :content="block"
                       :languages="languages"
                       :editable="isEditable"
                       :deleteable="isDeletetable"
            ></component>
                <div class="row" v-if="editable===1 && canAdd === 1">
                    <div class="button-container col-xs-6 col-sm-4 col-md-3 col-lg-2"
                         v-for="type in Object.keys(types)">
                    <button class="btn btn-block btn-default"
                            @click.prevent="addContentBlock(type)">Add {{type | ucword}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {filters} from "../filters/string"
    import * as Events from "../EventNames"

    export default {
      name    : "content-blocks",
      props   : ['contents', 'languages', 'editable', 'deleteable', 'canAdd', 'types'],
      data() {
        return {
          contentBlocks: [],
        }
      },
      computed: {
        isEditable() {
          return this.editable === 1
        },
        isDeletetable() {
          return this.deleteable === 1
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
          console.log('all types, ', this.types)
          console.log('parse content type, ', type)
          return this.types[type] ? this.types[type] : null
        },
        addContentBlock(type) {
          if (Object.keys(this.types).indexOf(type) >= 0) {
            this.contentBlocks.push({
                                      type      : type,
                                      identifier: null,
                                      changed   : true
                                    })
          }
        },
        setContentBlockDirty(identifier, state) {
          let contentBlock = _.find(this.contentBlocks, {identifier: identifier})
          if (contentBlock) {
            contentBlock.changed = state
          }
        },
        deleteContent(payload) {
          this.contentBlocks = _.filter(this.contentBlocks, item => item.identifier !== payload)
          console.log('from content blocks and payload is, ', payload)
        }
      }
    }
</script>

<style scoped>
    .button-container {
        margin-bottom: 15px;
    }
</style>