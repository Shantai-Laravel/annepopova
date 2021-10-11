<template>
  <div class="orders">
    <order-filter @updateOrders="updateOrders"></order-filter>

    <div class="card" v-if="orders.length > 0">
        <div class="card-block">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Email</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Payment</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                  <tr v-for="(order, index) in orders" :key="order.id">
                      <template v-if="order.user">
                        <td>
                            {{ index }}
                        </td>
                        <td>
                            {{ order.datetime }}
                        </td>
                        <td>
                          {{ order.user.email}}
                        </td>
                        <td>
                            {{ order.delivery }}
                        </td>
                        <td>
                            <select @change="changeStatus($event, order.id)">
                              <option :value="status" :selected="status == order.status" v-for="status in statuses">{{status}}</option>
                            </select>
                        </td>
                        <td>
                            {{ order.amount }}
                        </td>
                        <td>
                            {{ order.payment }}
                        </td>
                        <td>
                            <a :href="`/back/order/${order.id}/edit`"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="destroy-element">
                            <a href="#" @click.prevent="deleteOrder(order.id)"><i class="fa fa-trash"></i></a>
                        </td>
                      </template>
                  </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan=7></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="empty-response" v-else>
      No orders
    </div>

    <div class="loader-spiner" v-if="loading">
        <div class="lds-dual-ring"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      orders: [],
      statuses: ['created', 'pending', 'processing', 'inway', 'completed'],
      loading: false
    }
  },
  async created() {
    try {
      axios.interceptors.request.use(config => {
        this.loading = true
        return config;
      }, error => {
        return Promise.reject(error);
      });

      axios.interceptors.response.use(response => {
        this.loading = false

        return response;
      }, error => {
        this.loading = false
        return Promise.reject(error);
      });
      // get all orders
      const result = await axios.get('/back/order/getOrders')
      this.orders = result.data
    } catch(err) {
      console.log(err)
    }
  },
  methods: {
    // get filtered orders from OrderFilter
    updateOrders(data) {
      this.orders = data
    },
    // change order status
    async changeStatus(e, id) {
      try {
        const result = await axios.post('/back/order/changeStatus', {status: e.target.value, id})
        this.orders = this.orders.filter(order => order.id !== id)
      } catch(err) {
        console.log(err)
      }
    },
    // delete order
    async deleteOrder(id) {
      try {
        if(confirm("Do you want to delete this order ?")) {
          const result = await axios.delete('/back/order/' + id)
          this.orders = this.orders.filter(order => order.id !== id)
        }
      } catch(err) {
        console.log(err)
      }
    }
  }
}
</script>

<style scoped>
.loader-spiner {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.1);
    z-index: 999;
}
.lds-dual-ring {
    display: inline-block;
    width: 64px;
    height: 64px;
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -32px;
    margin-top: -32px;
}
.lds-dual-ring:after {
    content: " ";
    display: block;
    width: 46px;
    height: 46px;
    margin: 1px;
    border-radius: 50%;
    border: 5px solid #fff;
    border-color: #fff transparent #fff transparent;
    animation: lds-dual-ring 0.5s linear infinite;
}
@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
