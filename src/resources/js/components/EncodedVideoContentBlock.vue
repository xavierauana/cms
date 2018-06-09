<template>
    <base-content-block :identifier="content.identifier" :editable="editable"
                        :deleteable="deleteable" :type="type"
                        :changed="content.changed">
        <tabs :tabs="getTabIds(languages)">
            <div v-for="language in languages"
                 :slot="getTabId(language)">
                <div class="img-container" style="position:relative"
                     v-if="getFile(language.id)">
                    <img class="img-responsive"
                         :src="getFile(language.id).link" />
                    <button class="btn btn-sm btn-danger"
                            v-if="getFile(language.id).link.length > 0"
                            @click.prevent="removeCurrentFile(language.id)"
                            style="position: absolute; right:15px; bottom: 15px">Remove</button>
                </div>
                <input type="file"
                       @change.once="getDirty"
                       class="form-control"
                       :ref="getInputRef(language)"
                       :data-lang_id="language.id"
                       :placeholder="language.label + ' Content'"
                       :disabled="!editable"
                       content />
            </div>
        </tabs>
    </base-content-block>
</template>

<script>
     import Extension from "anacreation-cms-content-extension"

     export default {
       extends: Extension,
       name   : "encoded-video-content-block",
       data() {
         return {
           type : 'encodedVideo',
           files: [],
         }
       },
       methods: {
         hasFile(languageId) {
           return !!this.getFile(languageId)
         },
         getFile(languageId) {
           return _.find(this.files, {lang_id: languageId})
         },
         setValue(data) {
           let images = []
           _.forEach(data, item => {
             images.push({lang_id: item.lang_id, link: item.content.link})
           })
           this.files = images
         },
       }
     }
</script>
