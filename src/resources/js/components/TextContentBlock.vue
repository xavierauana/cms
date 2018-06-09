<template>
        <base-content-block :identifier="content.identifier"
                            :editable="editable"
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
    import * as Events from "../EventNames"
    import Extension from "anacreation-cms-content-extension"


    export default {
      extends: Extension,
      name   : "text-content-block",
      data() {
        return {
          editorClass: 'summernote-editor',
          type       : 'text',
          editors    : []
        }
      },
      methods: {
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
        },
        setValue() {
          let token = document.head.querySelector('meta[name="csrf-token"]').content;
          let editorOptions = {
            toolbar : ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'imageUpload', '|', 'undo', 'redo'],
            ckfinder: {
              uploadUrl     : '/filemanager/upload?command=QuickUpload&type=Files&responseType=json&_token=' + token,
              imageUploadUrl: '/filemanager/upload?command=QuickUpload&type=Images&responseType=json&_token=' + token,
              imageBrowseUrl: '/filemanager/?type=Images'
            }
          }

          ClassicEditor.build.plugins.map(plugin => console.log(plugin.pluginName));


          _.forEach(this.content.content, item => {
            ClassicEditor
              .create(this.getInputEl({id: item.lang_id}), editorOptions)
              .then(editor => {
                Array.from(editor.ui.componentFactory.names()).forEach(name => console.log(name))
                this.editors.push({id: item.lang_id, editor: editor})
                editor.setData(item.content)
              })
              .catch(error => console.error(error));
          })
        }
      }
    }
</script>
