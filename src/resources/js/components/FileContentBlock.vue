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

        <!--<tabs :tabs="getTabIds(languages)">-->
        <!--<div v-for="language in languages"-->
        <!--:slot="getTabId(language)">-->
        <!--<div class="img-container" style="position:relative"-->
        <!--v-if="hasFile(language.id)">-->
        <!--<p class="fileName">{{getFileName(language.id)}}</p>-->
        <!--<button class="btn btn-sm btn-danger"-->
        <!--v-if="hasFile(language.id)"-->
        <!--@click.prevent="removeCurrentFile(language.id)"-->
        <!--style="position: absolute; right:15px; bottom: 15px">Remove</button>-->
        <!--</div>-->

        <!--<progress-bar :progress="progress"-->
        <!--v-show="progress > 0"></progress-bar>-->
        <!--<input type="file"-->
        <!--@change.once="getDirty"-->
        <!--class="form-control"-->
        <!--:ref="getInputRef(language)"-->
        <!--:data-lang_id="language.id"-->
        <!--:placeholder="language.label + ' Content'"-->
        <!--:disabled="!editable"-->
        <!--content />-->
        <!--</div>-->
        <!--</tabs>-->
    </base-content-block>
</template>

<script>
                                        //import Extension from "anacreation-cms-content-extension"
                                        import Extension
                                          from "../packages/ContentBlockExtension"
                                        import * as Events from "../EventNames"

                                        export default {
                                          extends: Extension,
                                          name   : "file-content-block",
                                          data() {
                                            return {
                                              type    : 'file',
                                              files   : [],
                                              progress: 0
                                            }
                                          },
                                          mounted() {
                                            console.log('register event for upload progress')
                                            NotificationCenter.$on(Events.UPLOAD_PROGRESS, this.updateProgressBar)
                                          },
                                          methods: {
                                            updateProgressBar(payload) {
                                              console.log('get notification for file component', payload)
                                              if (payload.identifier === this.content.identifier) {
                                                const totalLength = payload.progressEvent.lengthComputable ? payload.progressEvent.total : payload.progressEvent.target.getResponseHeader('content-length') || payload.progressEvent.target.getResponseHeader('x-decompressed-content-length');
                                                console.log("onUploadProgress", totalLength);
                                                if (totalLength !== null) {
                                                  this.progress = Math.round((payload.progressEvent.loaded * 100) / totalLength);
                                                }
                                              }
                                            },
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
