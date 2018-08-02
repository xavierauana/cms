<template>
    <base-content-block :identifier="content.identifier"
                        :editable="editable"
                        :deleteable="deleteable"
                        :type="type"
                        :changed="content.changed"
                        :languages="languages">
         <b-tabs>
                 <b-tab v-for="language in languages"
                        :key="language.id"
                        :title="language.label">
                             <input @keydown.once="getDirty"
                                    type="text"
                                    class="form-control"
                                    :ref="getInputRef(language)"
                                    :data-lang_id="language.id"
                                    :placeholder="'Datetime for ' + language.label"
                                    :disabled="!editable"
                                    content />
                 </b-tab>
         </b-tabs>

    </base-content-block>
</template>
<script>

    import flatpickr from 'flatpickr'
    import Extension from "../packages/ContentBlockExtension"

    export default {
      extends: Extension,
      name   : "DatetimeContentBlock",
      mounted() {
        this.languages.map(language => this.getInputEl(language))
            .forEach(el => flatpickr(el, {
              enableTime: true,
              dateFormat: "Y-m-d H:i"
            }))
      },
      data() {
        return {
          type: 'datetime'
        }
      }
    }
</script>
<style scoped>
    .form-control[readonly] {
        background-color: white
    }
</style>