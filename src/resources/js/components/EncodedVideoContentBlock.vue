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
                   <div class="img-container" style="position:relative"
                        v-if="hasFile(language.id)">
                    <p class="fileName">{{getFileName(language.id)}}</p>
                    <button class="btn btn-sm btn-danger"
                            v-if="hasFile(language.id)"
                            @click.prevent="removeCurrentFile(language.id)"
                            style="position: absolute; right:15px; bottom: 15px">Remove</button>
                </div>

                <progress-bar :progress="progress"
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
       name   : "encoded-video-content-block",
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
