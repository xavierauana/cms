/**
 * Created by Xavier on 11/1/2018.
 */
import * as Events from "../EventNames"

export default {
  props  : ['identifier', 'languages', 'editable', 'deleteable'],
  data() {
    return {
      open           : false,
      inputIdentifier: "",
      contentBlockId : null,
    }
  },
  created() {
    this.contentBlockId = 'content_block_' + this._uid
    if (this.identifier) {
      this.inputIdentifier = this.identifier
    }
  },
  methods: {
    getDirty() {
      NotificationCenter.$emit(Events.CONTENT_GET_DIRTY, this.identifier)
    },
    remove(identifier) {
      console.log(this.identifier, identifier)
      if (confirm("Deleted item cannot be retrieved. Are you sure to go ahead?")) {
        const href = window.location.href,
              last = href.split("")[href.split("").length - 1],
              uri  = last !== "/" ? `${href}/${this.identifier}` : href + this.identifier
        console.log('uri is, ', this.uri)
        axios.delete(uri)
             .then(({data}) => NotificationCenter.$emit(Events.CONTENT_DELETED, data.identifier))
      }
    },
    update() {
      const url = "/" + window.location.pathname.split("/").filter(segment => segment.length > 0).join('/') + "/update",
            key = 'content_block_' + this._uid,
            el  = this.$refs[key].$el

      let inputs = [
        ...this.constructSummernoteEditorData(el),
        ...el.querySelectorAll("select[content]"),
        ...el.querySelectorAll("input[content]")
      ]

      const inputsData = this.constructInputsData(inputs)

      let requests = _.map(inputsData, data => axios.post(url, data, {
        onUploadProgress: progressEvent => {
          NotificationCenter.$emit(Events.UPLOAD_PROGRESS, {
            identifier   : this.identifier,
            progressEvent: progressEvent
          })
        },

      }))

      NotificationCenter.$emit(Events.UPLOAD_START)
      axios.all(requests).then((...responses) => this.successfullyUpdated(responses, this.identifier))

    },
    constructInputsData(inputs) {
      let tempDataContainer = []
      _.chain(inputs)
       .map(input => this.parseDataFromInputs(input))
       .forEach(input => {
         const result = _.find(tempDataContainer, {lang_id: input.lang_id})
         if (!result) {
           tempDataContainer.push(input)
         } else {
           if (result.hasOwnProperty('content')) {
             let tempArray = [];
             if (Array.isArray(result.content)) {
               tempArray = [
                 ...result.content,
                 input.content
               ]
             } else {
               tempArray = [
                 result.content,
                 input.content
               ]
             }
             result.content = tempArray
           }
         }
       })
       .value()
      return tempDataContainer
    },
    successfullyUpdated(responseData, identifier) {
      alert('Update Completed')
      NotificationCenter.$emit(Events.CONTENT_GET_CLEAN, identifier)
    },
    parseDataFromInputs(input) {

      let inputTagName = input.tagName;
      let data = {}

      switch (inputTagName) {
        case "TEXTAREA":
          data = {
            identifier  : this.inputIdentifier,
            content_type: this.type,
            lang_id     : input.dataset.lang_id,
            content     : $(input).summernote('code'),
          }
          break;
        case "SELECT":
          data = {
            identifier  : this.inputIdentifier,
            content_type: this.type,
            lang_id     : input.dataset.lang_id,
            content     : input.value,
          }
          break;
        default:
          data = this.parseDataFromInputEl(input)
      }
      return data
    },
    parseDataFromInputEl(input) {
      let data = {}
      switch (input.getAttribute('type').toLocaleLowerCase()) {
        case "file":
          let formData = new FormData()
          formData.append('content', input.files[0])
          formData.append('identifier', this.inputIdentifier)
          formData.append('content_type', this.type)
          formData.append('lang_id', input.dataset.lang_id)
          data = formData
          break
        case "checkbox":
          data = {
            identifier  : this.inputIdentifier,
            content_type: this.type,
            lang_id     : input.dataset.lang_id,
            content     : input.checked ? input.value : "",
          }
          break
        default:
          data = {
            identifier  : this.inputIdentifier,
            content_type: this.type,
            lang_id     : input.dataset.lang_id,
            content     : input.value
          }

      }
      return data
    },
    constructSummernoteEditorData(el) {
      const textareas = el.querySelectorAll("textarea[content]")
      // Construct Summernote editor content e.g. <textarea class='summernote-editor'></textarea>
      return _.chain(textareas)
              .map(editor => editor.className.indexOf('summernote-editor') > -1 ? editor : null)
              .filter(editor => !!editor)
              .value()
    },
  }
}
