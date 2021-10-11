<template>
  <div class="create-order">
    <select class="form-control" name="users" v-model="user_id" @change="filterUsers" v-if="showUsers">
        <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
    </select>

    <select class="form-control" name="orders" @change="filterOrders" v-if="showUsers">
        <option selected value="">Select order for return</option>
        <option :value="order.id" v-for="order in preparedItems">Order nr. {{order.id}}</option>
    </select>

    <return @hideUsers="showUsers = false" v-if="ready"
      :user_id="user_id"
      :return_id="returnId"
      :products="returnProducts"
      :sets="returnSets"
      @remove="preparedOrders">
    </return>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      user_id: null,
      orders: [],
      preparedItems: [],
      returnId: null,
      returnProducts: [],
      returnSets: [],
      ready: false,
      showUsers: true,
      showOrders: false
    }
  },
  async created() {
    try {
      // get all users
      const result = await axios.get('/back/returns/getUsers')
      this.users = result.data.users
      this.user_id = this.users[0].id
      this.orders = result.data.orders
      this.returnId = result.data.returnId
      this.returnProducts = result.data.returnProducts
      this.returnSets = result.data.returnSets
      this.ready = true
    } catch(err) {
      console.log(err)
    }

    this.preparedOrders(this.returnProducts, this.returnSets);
  },
  methods: {
      inArray(id) {
        let ret = false;
        this.preparedItems.forEach(function(order){
            if (order.id === id) {
                ret = true;
                return ret;
            }
        })
        return ret;
    },
    arrayFilter(){
        let filtered = this.preparedItems.filter(function (el) {
            return el != null;
        });
        return filtered;
    },
    // get orders which are not in return
    preparedOrders(returnProducts, returnSets) {
        if (returnProducts.length === 0 && returnSets.length === 0) {
            this.preparedItems = this.orders
        } else {
            let stopLoopSets = false;
            let vm = this;
            this.preparedItems = [];

            this.orders.forEach(function(order) {
                returnProducts.forEach(function(returnProduct) {
                    if (returnProduct.order_product.order_id === order.id) {
                        delete vm.preparedItems[order.id];
                    } else {
                        vm.preparedItems[order.id] = order
                    }
                })

                returnSets.forEach(function(returnSet) {
                    if (returnSet.return_products[0].order_product.order_id !== order.id) {
                        if (!vm.inArray(order.id)) {
                            vm.preparedItems[order.id] = order
                        }
                    } else {
                        delete vm.preparedItems[order.id];
                        stopLoopSets = order;
                    }
                })

                if (stopLoopSets) {
                    delete vm.preparedItems[stopLoopSets.id];
                    stopLoopSets = false;
                }
            });

            this.preparedItems = this.arrayFilter();
        }
    },
    // get products which are not in return
    getPreparedProducts(order) {
      let ret = false;
      if(this.returnProducts.length > 0) {
        this.returnProducts.forEach(returnProduct => {
            if(returnProduct.order_product.order_id !== order.id) {
                ret = order;
                return ret;
            }
        })
      }
      return ret;
    },
    // get sets which are not in return
    getPreparedSets(order) {
      let ret = false;
      if(this.returnSets.length > 0) {
        this.returnSets.forEach(returnSet => {
            if(returnSet.return_products[0].order_product.order_id !== order.id) {
                ret = order;
                return ret;
            }
        })
      }
      return ret;

    },
    // filter users by id
    async filterUsers() {
      try {
        this.ready = false
        const result = await axios.post('/back/returns/filterUsers', {user_id: this.user_id})
        this.orders = result.data.orders
        this.returnId = result.data.returnId
        this.returnProducts = result.data.returnProducts
        this.returnSets = result.data.returnSets
        this.ready = true
        this.preparedOrders(this.returnProducts, this.returnSets)
      } catch(err) {
        console.log(err)
      }
    },
    // filter orders
    async filterOrders(e) {
      try {
        if(e.target.value !== '') {
          this.ready = false
          const result = await axios.post('/back/returns/filterOrders', {id: e.target.value})
          e.target.value = ''
          this.returnId = result.data.returnId
          this.returnProducts = result.data.returnProducts
          this.returnSets = result.data.returnSets
          this.ready = true
          this.preparedOrders(this.returnProducts, this.returnSets)
        }
      } catch(err) {
        console.log(err)
      }
    }
  }
}
</script>
