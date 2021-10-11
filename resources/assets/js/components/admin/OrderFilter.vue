<template>
  <div class="row filterOrders">
    <label class="radio-inline radPlus" v-for="(filter, index) in filters" :key="index">
      <input type="radio" name="inlineRadioOptions" :checked="selectedFilter == filter" :value="filter" @click="filterOrders">
      <span class="spanRad">{{filter}}</span>
    </label>
  </div>
</template>

<script>
export default {
  data() {
    return {
      filters: ['created', 'pending', 'processing', 'inway', 'completed'],
      selectedFilter: 'created'
    }
  },
  methods: {
    // filter orders by status
    async filterOrders(e) {
        try {
          this.selectedFilter = e.target.value
          const result = await axios.post('/back/order/filterOrders', {status: this.selectedFilter})
          // emit event in Orders
          this.$emit('updateOrders', result.data)
        } catch(err) {
          console.log(err)
        }
    }
  }
}
</script>
