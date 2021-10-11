<template>
  <div class="create-order">
    <select class="form-control" name="users" v-model="user_id" @change="filterUsers" v-if="showUsers" >
        <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
    </select>

    <order-admin @hideUsers="showUsers = false" v-if="ready"
      :user_id="user_id"
      :order_id="order_id"
      :products="orderProducts"
      :sets="orderSets">
    </order-admin>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      user_id: null,
      order_id: null,
      orderProducts: [],
      orderSets: [],
      ready: false,
      showUsers: true
    }
  },
  async created() {
    try {
      // get all users and orders
      const result = await axios.get('/back/order/getUsers')
      this.users = result.data.users
      this.user_id = this.users[0].id
      this.order_id = result.data.orderId
      this.orderProducts = result.data.orderProducts
      this.orderSets = result.data.orderSets
      this.ready = true
    } catch(err) {
      console.log(err)
    }
  },
  methods: {
    // filter user by id and get his orders
    async filterUsers() {
      try {
        this.ready = false
        const result = await axios.post('/back/order/filterUsers', {user_id: this.user_id})
        this.order_id = result.data.orderId
        this.orderProducts = result.data.orderProducts
        this.orderSets = result.data.orderSets
        this.ready = true
      } catch(err) {
        console.log(err)
      }
    }
  }
}
</script>
