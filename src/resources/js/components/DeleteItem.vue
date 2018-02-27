<script>
export default {
  name   : "delete-item",
  props  : ['url'],
  methods: {
    deleteItem(id) {
      if (confirm('Are you sure you want to delete the item?')) {
        axios.delete(this.url + id)
             .then(response => {
               const el = this.$refs.table.querySelector("[data-id='" + id + "']")
               el ? el.remove() : ""
               return response
             })
             .then(response => alert("Item has been deleted!"))
             .catch(error => console.log(error.response))

      }
    }
  }
}
</script>