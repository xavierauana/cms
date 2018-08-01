<template>
    <base-content-block :identifier="content.identifier"
                        :editable="editable"
                        :deleteable="deleteable"
                        :type="type"
                        :languages="languages"
                        :changed="content.changed"
                        class="encoded-video-content-block">
        <b-tabs>
              <b-tab v-for="language in languages"
                     :key="language.id"
                     :title="language.label">
                   <div class="content-container"
                        v-if="hasFile(language.id)">
                    <p class="fileName">{{getFileName(language.id)}}</p>
                    <button class="btn btn-sm btn-danger remove-button"
                            v-if="hasFile(language.id)"
                            @click.prevent="removeCurrentFile(language.id)">Remove</button>
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

     export default {
       extends: Extension,
       name   : "EncodedVideoContentBlock",
       data() {
         return {
           type    : 'encoded_video',
           files   : [],
           progress: 0
         }
       },
       methods: {
         hasFile(languageId) {
           return !!this.getFile(languageId)
         },
         getFile(languageId) {
           return _.find(this.files, {lang_id: languageId})
         },
         getFileName(languageId) {
           const file = this.getFile(languageId)

           return file.src || ''
         },
         setValue(data) {
           _.forEach(data, item => {
             this.files.push({
                               lang_id: item.lang_id,
                               src    : item.content
                             })
           })
         },
       }
     }
</script>

<style scoped>
    .encoded-video-content-block .content-container {
        position: relative;
        padding: 0 15px;
        background-color: lightgrey;
        border-radius: 5px;
        margin: 15px 0;
        height: 40px;
    }

    .encoded-video-content-block .content-container .fileName {
        margin: 0;
        line-height: 40px;
    }

    .encoded-video-content-block .content-container .remove-button {
        position: absolute;
        height: 30px;
        right: 5px;
        top: 5px
    }

</style>
