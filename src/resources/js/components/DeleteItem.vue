<script>
export default {
  name   : "delete-item",
  props  : {
    url: {
      type    : String,
      required: true
    }
  },
  methods: {
    deleteItem(id) {
      if (confirm('Are you sure you want to delete the item?')) {
        const request = id ? axios.delete(this.url + id) : axios.delete(this.url)
        request
          .then(response => {
            alert("Item has been deleted!")
            window.location.reload()
            //const el = this.$refs.table.querySelector("[data-id='" + id + "']")
            //el ? el.remove() : ""
          })
          .catch(error => console.log(error.response))

      }
    }
  }
}
</script>