<template>
  <div class="returns">
    <return-filter :amount="userfield.value" @updateReturns="updateReturns"></return-filter>

    <div class="card" v-if="returns.length > 0">
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
                  <tr v-for="(retur, index) in returns" :key="retur.id">
                      <td>
                          {{ index }}
                      </td>
                      <td>
                          {{ retur.datetime }}
                      </td>
                      <td>
                        {{ retur.user.email}}
                      </td>
                      <td>
                          {{ retur.delivery }}
                      </td>
                      <td>
                          <select @change="changeStatus($event, retur.id)">
                            <option :value="status" :selected="status == retur.status" v-for="status in statuses">{{status}}</option>
                          </select>
                      </td>
                      <td>
                          {{ retur.amount }}
                      </td>
                      <td>
                          {{ retur.payment }}
                      </td>
                      <td>
                          <a :href="`/back/returns/${retur.id}/edit`"><i class="fa fa-edit"></i></a>
                      </td>
                      <td class="destroy-element">
                          <a href="#" @click.prevent="deleteReturn(retur.id)"><i class="fa fa-trash"></i></a>
                      </td>
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
      No returns
    </div>

    <div class="loader-spiner" v-if="loading">
        <div class="lds-dual-ring"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['userfield'],
  data() {
    return {
      returns: [],
      statuses: ['new', 'processing', 'cancelled', 'completed'],
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

      // get all returns
      const result = await axios.get('/back/returns/getReturns')
      this.returns = result.data
    } catch(err) {
      console.log(err)
    }
  },
  methods: {
    // update filtered returns from ReturnFilter
    updateReturns(data) {
      this.returns = data
    },
    // change return status
    async changeStatus(e, id) {
      try {
        const result = await axios.post('/back/returns/changeStatus', {status: e.target.value, id})
        this.returns = this.returns.filter(returnOne => returnOne.id !== id)
      } catch(err) {
        console.log(err)
      }
    },
    // delete return
    async deleteReturn(id) {
      try {
        if(confirm("Do you want to delete this order ?")) {
          const result = await axios.delete('/back/returns/' + id)
          this.returns = this.returns.filter(returnOne => returnOne.id !== id)
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
