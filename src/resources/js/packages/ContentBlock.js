/**
 * Created by Xavier on 11/1/2018.
 */

import * as Events from "./EventNames"

export default {
  props  : {
    languages : {
      type    : Array,
      required: true
    },
    editable  : {
      type    : Boolean,
      required: true
    },
    deleteable: {
      type    : Boolean,
      required: true
    },
    content   : {
      type    : Object,
      required: true
    },
    type      : {
      type    : String,
      required: true
    },
  },
  mounted() {
    this.setValue()
  },
  methods: {
    getDirty() {
      NotificationCenter.$emit(Events.CONTENT_GET_DIRTY, this.content.identifier)
    },
    getInputRef(language) {
      return 'input_lang_' + this._uid + "_" + language.id
    },
    getInputEl(language) {
      return this.$refs[this.getInputRef(language)][0]
    },
    getTabId(language) {
      return 'tab_' + this._uid + "_" + language.id
    },
    getTabIds(languages) {
      return languages.map(language => {
        return {
          label: language.label,
          id   : this.getTabId(language)
        }
      })
    },
    setInputId(lang_id) {
      return `input.${lang_id}.${this.content.identifier}`
    },
    setValue() {
      _.forEach(this.content.content, item => {
        const el = this.$refs[this.getInputRef({id: item.lang_id})][0]
        el.value = item.content
      })
    },
    removeCurrentFile(lang_id) {
      if (confirm("Deleted item cannot be retrieved. Are you sure to go ahead?")) {
        const pathname  = window.location.pathname,
              firstPart = "/" + pathname.split("/").filter(segment => segment.length > 0).join('/'),
              query     = "remove_content=1&lang_id=" + lang_id,
              uri       = firstPart + "/" + this.content.identifier + "?" + query
        axios.delete(uri)
             .then(({data}) => this.files.splice(_.findIndex(this.files, {lang_id: lang_id}), 1))
      }
    }
  }
}