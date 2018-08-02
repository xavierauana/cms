/**
 * Created by Xavier on 11/1/2018.
 */
import * as Events from "../EventNames"

export default {
  props  : {
    identifier: {
      type    : String,
      required: true
    },
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
    type      : {
      type    : String,
      required: true
    },
    changed   : {
      type    : Boolean,
      required: true
    },
  },
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
      console.log('fired dirty event')
      NotificationCenter.$emit(Events.CONTENT_GET_DIRTY, this.identifier)
    },
    remove(identifier) {
      if (confirm("Deleted item cannot be retrieved. Are you sure to go ahead?")) {
        const href = window.location.href,
              last = href.split("")[href.split("").length - 1],
              uri  = last !== "/" ? `${href}/${this.identifier}` : href + this.identifier
        axios.delete(uri)
             .then(({data}) => NotificationCenter.$emit(Events.CONTENT_DELETED, data.identifier))
      }
    },
    update(e) {
      const url = "/" + window.location.pathname.split("/").filter(segment => segment.length > 0).join('/') + "/update",
            el  = e.target

      let inputs = [
        ...el.querySelectorAll("textarea[content]"),
        ...el.querySelectorAll("select[content]"),
        ...el.querySelectorAll("input[content]")
      ]

      const inputsData = this.constructInputsData(inputs)

      const requests = _.map(inputsData, data => axios.post(url, data, {
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
      NotificationCenter.$emit(Events.CONTENT_GET_CLEAN, identifier)
    },
    parseDataFromInputs(input) {

      let inputTagName = input.tagName;
      let data = {
        identifier  : this.inputIdentifier,
        content_type: this.type,
        lang_id     : input.dataset.lang_id,
      }

      switch (inputTagName) {
        case "TEXTAREA":
          data['content'] = CKEDITOR.instances[input.id].getData()
          break;
        case "SELECT":
          data['content'] = input.value
          break;
        default:
          data = this.parseDataFromInputEl(input, data)
      }
      return data
    },
    parseDataFromInputEl(input, data) {
      switch (input.getAttribute('type').toLocaleLowerCase()) {
        case "file":
          let formData = new FormData()
          const inputFile = input.files[0]
          console.log(input.files)
          console.log(inputFile)
          formData.append('content', inputFile)
          formData.append('identifier', data['identifier'])
          formData.append('content_type', data['content_type'])
          formData.append('lang_id', data['lang_id'])
          data = formData
          break
        case "checkbox":
          data["content"] = input.checked ? input.value : ""
          break
        default:
          data["content"] = input.value
      }
      return data
    }
  }
}
