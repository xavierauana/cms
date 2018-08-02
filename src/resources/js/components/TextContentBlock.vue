<template>
        <base-content-block :identifier="content.identifier"
                            :editable="editable"
                            :deleteable="deleteable"
                            :type="type"
                            :languages="languages"
                            :changed="content.changed">
            <b-tabs>
                 <b-tab v-for="language in languages"
                        :key="language.id"
                        :title="language.label">
                     <textarea :id="setInputId(language.id)"
                               :ref="getInputRef(language)"
                               :data-lang_id="language.id"
                               class="form-control"
                               :class="editorClass"
                               :placeholder="language.label + ' Content'"
                               rows="10"
                               :disabled="!editable"
                               content
                     ></textarea>
                 </b-tab>
            </b-tabs>
    </base-content-block>
</template>

<script>
    import * as Events from "../EventNames"
    import Extension from "../packages/ContentBlockExtension"

    const options = {
      filebrowserImageBrowseUrl: '/filemanager?type=Images',
      filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
      filebrowserBrowseUrl     : '/filemanager?type=Files',
      filebrowserUploadUrl     : '/filemanager/upload?type=Files&_token='
    }

    export default {
      extends: Extension,
      name   : "TextContentBlock",
      data() {
        return {
          editorClass: 'summernote-editor',
          type       : 'text'
        }
      },
      methods: {
        setValue() {
          _.forEach(this.content.content, item => {
            const el = document.getElementById(this.setInputId(item.lang_id))
            if (el) el.value = item.content
          })

          _.chain(this.languages)
           .map(language => CKEDITOR.replace(this.setInputId(language.id), options))
           .forEach(editor => editor.on('change', e => NotificationCenter.$emit(e.editor.checkDirty() ? Events.CONTENT_GET_DIRTY : Events.CONTENT_GET_CLEAN, this.content.identifier)))
           .value()
        }
      }
    }
</script>
