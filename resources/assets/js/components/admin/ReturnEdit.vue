<template>
  <div class="edit-order">

    <select class="form-control" name="orders" @change="filterOrders" v-if="showUsers">
        <option selected value="">Select order for return</option>
        <option :value="order.id" v-for="order in preparedItems">Order nr. {{order.id}}</option>
    </select>

    <return @hideUsers="showUsers = false" v-if="ready"
      :userReturn="userReturn"
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
      userReturn: null,
      orders: [],
      preparedItems: [],
      returnProducts: [],
      returnSets: [],
      ready: false,
      showUsers: true,
    }
  },
  async created() {
    try {
      // get return by id
      const result = await axios.get(`/back/returns/getReturn/${ location.href.match(/[0-9]+/)[0] }`)
      this.userReturn = result.data.return
      this.orders = result.data.orders
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
    // filter orders
    async filterOrders(e) {
      try {
        if(e.target.value !== '') {
          this.ready = false
          const result = await axios.post('/back/returns/filterOrders', {id: e.target.value, return_id: this.userReturn.id})
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
