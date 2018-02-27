<template>
    <base-content-block :identifier="content.identifier" :editable="editable"
                        :deleteable="deleteable" :type="type"
                        :languages="languages"
                        :changed="content.changed">
        <tabs :tabs="getTabIds(languages)">
            <textarea v-for="language in languages"
                      :key="language.id"
                      :ref="getInputRef(language)"
                      :data-lang_id="language.id"
                      :slot="getTabId(language)" class="form-control"
                      :class="editorClass"
                      :placeholder="language.label + ' Content'"
                      rows="10"
                      :disabled="!editable"
                      content
            ></textarea>
        </tabs>
    </base-content-block>
</template>

<script>
    require('summernote')
    import BaseContentBlock from "./BaseContentBlock"
    import ContentMixin from "../mixins/ContentBlock"
    import * as Events from "../EventNames"

    require('summernote')
    require('summernote/dist/summernote.css');

    export default {
      name      : "text-content-block",
      components: {
        BaseContentBlock
      },
      mixins    : [ContentMixin],
      data() {
        return {
          editorClass: 'summernote-editor',
          type       : 'text'
        }
      },
      methods   : {
        setupFileManager() {
          const fileManagerPrefix = '/filemanager';
          const lfm = (options, cb) => {
            const route_prefix = (options && options.prefix) ? options.prefix : fileManagerPrefix;
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
          }
          const imageCallBack = context => {
            lfm({
                  type  : 'image',
                  prefix: fileManagerPrefix
                }, (url, path) => context.invoke('insertImage', url));
          }
          const buttonOptions = context => {
            return {
              contents: '<i class="note-icon-picture"></i> ',
              tooltip : 'Insert image',
              click   : () => imageCallBack(context)
            }
          }
          const LFMButton = context => {
            return $.summernote.ui
                    .button(buttonOptions(context))
                    .render();
          }
          const toolBar = [
            ['style', ['fontsize', 'bold', 'italic', 'underline', 'color', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph', 'height']],
            ['insert', ['link', 'lfm', 'video', 'table', 'hr']],
            ['misc', ['fullscreen', 'codeview', 'undo', 'redo']],
          ];
          const options = {
            toolbar  : toolBar,
            buttons  : {
              lfm: LFMButton
            },
            height   : 300,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            callbacks: {
              onInit       : () => {
                console.log('Summernote is launched');
              },
              onKeydown    : () => {
                NotificationCenter.$emit(Events.CONTENT_GET_DIRTY, this.content.identifier)
              },
              onImageUpload: function (files, editor, welEditable) {
                $(this).summernote('insertNode', img)
              }
            },
          }
          this.languages.forEach(language => $(this.getInputEl(language)).summernote(options))
        },
        setValue() {
          this.setupFileManager()
          _.forEach(this.content.content, item => $(this.getInputEl({id: item.lang_id})).summernote('code', item.content))
        }
      }
    }
</script>
