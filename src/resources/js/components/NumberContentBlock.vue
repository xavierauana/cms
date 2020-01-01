<template>
    <base-content-block :identifier="content.identifier"
                        :editable="editable"
                        :deleteable="deleteable"
                        :languages="languages"
                        :type="type"
                        :changed="content.changed">
         <b-tabs>
              <b-tab v-for="language in languages"
                     :key="language.id"
                     :title="language.label">

                  <input @keydown.once="getDirty"
                         :id="setInputId(language)"
                         type="number"
                         class="form-control"
                         :ref="getInputRef(language)"
                         :data-lang_id="language.id"
                         :placeholder="language.label + ' Content'"
                         :disabled="!editable"
                         content />

              </b-tab>
             <div class="helper-text"><em v-text="content.helper"></em></div>
         </b-tabs>

    </base-content-block>
</template>

<script>
     import Extension from "../packages"

     export default {
       extends: Extension,
       name   : "number-content-block",
       props  : ['identifier', 'editable', 'deleteable'],
       created() {
         if (this.identifier) {
           this.loadContent()
         }
       }
     }
</script>
