<template>
    <base-content-block :identifier="content.identifier"
                        :editable="editable"
                        :deleteable="deleteable"
                        :type="type"
                        :languages="languages"
                        :changed="content.changed"
                        class="file-content-block">
         <b-tabs>
              <b-tab v-for="language in languages"
                     :key="language.id"
                     :title="language.label">
                   <div class="content-container" style="position:relative"
                        v-if="hasFile(language.id)">
                    <p class="fileName">{{getFileName(language.id)}}</p>
                    <button class="btn btn-sm btn-danger remove-button"
                            v-if="hasFile(language.id)"
                            @click.prevent="removeCurrentFile(language.id)"
                            style="position: absolute; right:15px; bottom: 15px">Remove</button>
                </div>

                <progress-bar
                        :progress="progress"
                        v-show="progress > 0"></progress-bar>
                <input type="file"
                       @change.once="getDirty"
                       class="form-control"
                       :ref="getInputRef(language)"
                       :data-lang_id="language.id"
                       :placeholder="language.label + ' Content'"
                       :disabled="!editable"
                       content />
              </b-tab>
         </b-tabs>
    </base-content-block>
</template>

<script>
import Extension from "../packages/ContentBlockExtension"
import * as Events from "../EventNames"

export default {
  extends: Extension,
  name   : "FileContentBlock",
  data() {
    return {
      type    : 'file',
      files   : [],
      progress: 0
    }
  },
  mounted() {
    NotificationCenter.$on(Events.UPLOAD_PROGRESS, this.updateProgressBar)
  },
  methods: {
    updateProgressBar(payload) {
      if (payload.identifier === this.content.identifier) {
        const totalLength = this.getTotalLength(payload)
        if (totalLength !== null) {
          this.progress = Math.round((payload.progressEvent.loaded * 100) / totalLength);
        }
      }
    },
    getTotalLength(payload) {
      if (payload.progressEvent.lengthComputable) {
        return payload.progressEvent.total
      } else {
        return payload.progressEvent.target.getResponseHeader('content-length')
          || payload.progressEvent.target.getResponseHeader('x-decompressed-content-length');
      }
    },
    hasFile(languageId) {
      const result = this.getFile(languageId)

      return result && result.src.length
    },
    getFile(languageId) {
      return _.find(this.files, {lang_id: languageId})
    },
    getFileName(languageId) {
      return this.getFile(languageId).src || ''
    },
    setValue() {
      _.forEach(this.content.content, item => {
        this.files.push({
                          lang_id: item.lang_id,
                          src    : item.content
                        })
      })
    },
  }
}
</script>

<style>
    .file-content-block .content-container {
        position: relative;
        padding: 0 15px;
        background-color: lightgrey;
        border-radius: 5px;
        margin: 15px 0;
        height: 40px;
    }

    .file-content-block .content-container .fileName {
        margin: 0;
        line-height: 40px;
    }

    .file-content-block .content-container .remove-button {
        position: absolute;
        height: 30px;
        right: 5px;
        top: 5px
    }
</style>
