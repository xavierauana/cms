<template>
    <base-content-block :identifier="content.identifier" :editable="editable"
                        :deleteable="deleteable" :type="type"
                        :changed="content.changed">
        <tabs :tabs="getTabIds(languages)">
            <div v-for="language in languages"
                 :slot="getTabId(language)">
                <div class="img-container" style="position:relative"
                     v-if="getFile(language.id)">
                    <p class="fileName">{{getFileName(language.id)}}</p>
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
       name   : "file-content-block",
       data() {
         return {
           type : 'file',
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
         setValue() {
           _.forEach(this.content.content, item => {
             this.files.push({lang_id: item.lang_id, src: item.content})
           })
         },
       }
     }
</script>
