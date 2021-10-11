<template>
  <div class="edit-order">
    <order-admin v-if="ready"
      :userOrder="userOrder"
      :products="orderProducts"
      :sets="orderSets">
    </order-admin>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userOrder: null,
      orderProducts: [],
      orderSets: [],
      ready: false
    }
  },
  async created() {
    try {
      // get order by id
      const result = await axios.get(`/back/order/getOrder/${ location.href.match(/[0-9]+/)[0] }`)
      this.userOrder = result.data.order
      this.orderProducts = result.data.orderProducts
      this.orderSets = result.data.orderSets
      this.ready = true
    } catch(err) {
      console.log(err)
    }
  }
}
</script>
