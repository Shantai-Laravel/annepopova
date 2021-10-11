<template>
  <div class="row filterReturns">
    <label class="radio-inline" v-for="(filter, index) in filters" :key="index">
      <input type="radio" name="inlineRadioOptions" :checked="selectedFilter == filter" :value="filter" @click="filterReturns">
      <span class="spanRad">{{filter}}</span>
    </label>

    <div class="row">
      <label class="radio-inline">
        <input type="text" name="returnAmount" v-model="returnAmount">
        <input type="submit" name="submit" value="OK" @click="changeAmount(returnAmount)">
      </label>
    </div>
  </div>
</template>

<script>
export default {
  props: ['amount'],
  data() {
    return {
      returnAmount: this.amount,
      filters: ['new', 'processing', 'cancelled', 'completed'],
      selectedFilter: 'new'
    }
  },
  methods: {
    // filter returns by selected filter
    async filterReturns(e) {
        try {
          this.selectedFilter = e.target.value
          const result = await axios.post('/back/returns/filterReturns', {status: this.selectedFilter})
          // emit event Returns
          this.$emit('updateReturns', result.data)
        } catch(err) {
          console.log(err)
        }
    },
    // change return amount days
    async changeAmount(amount) {
        try {
          const result = await axios.post('/back/returns/changeAmount', {amount})
        } catch(err) {
          console.log(err)
        }
    }
  }
}
</script>
