<template>
  <div class="order">
    <div class="step1" v-if="step == 1">
      <div class="cartItems" v-if="orderItemsLength > 0">
         <div class="row headCart">
          <div class="col-md-1">
          </div>
          <div class="col-md-1">
            Order
          </div>
          <div class="col-md-3">
            Produs
          </div>
          <div class="col-md-2">
            Cantitate
          </div>
          <div class="col-md-2">
            Total lei
          </div>
        </div>

        <div class="row cartOneItem" v-for="returnProduct in returnProducts" >
          <div class="one-item">
            <div class="col-md-1">
              <img src="/fronts/img/icons/trashIcon.png" class="buttonRemove removeItem" @click="removeProduct(returnProduct.id)" style="width: 40px; height: 40px; cursor: pointer;">
            </div>
            <div class="col-md-1">
              {{returnProduct.order_product.order_id}}
            </div>
            <div class="col-lg-3 col-md-12">
              <div class="imgCartItem">
                <img v-if="returnProduct.product.main_image" :src="`/images/products/sm/${returnProduct.product.main_image.src}`" class="cartImg">
                <img v-else src="/images/no-image.png" alt="">
              </div>
              <div class="cartDescr">
                <p>{{returnProduct.product.translation.name}}</p>
                <p v-if="returnProduct.subproduct">Marime: {{returnProduct.subproduct.parameter_value.translation.name}}</p>
              </div>
            </div>
            <div class="col-lg-2 col-6 justify-content-center ngh">
              <select class="form-control" v-model="returnProduct.qty" @change="changeProductQty($event, returnProduct.id)">
                  <option :value="qty" v-for="qty in returnProduct.order_product.qty">{{ qty }}</option>
              </select>
            </div>
            <div class="col-md-2">
              {{ returnProduct.order_product.actual_price * returnProduct.qty}}
            </div>
          </div>
        </div>

        <div class="row set" v-for="returnSet in returnSets" :key="returnSet.id">
          <div class="row cartOneItem">
            <div class="col-md-1">
              <img src="/fronts/img/icons/trashIcon.png" class="buttonRemoveSet removeItem" @click="removeSet(returnSet.id)" style="width: 40px; height: 40px; cursor: pointer;">
            </div>
            <div class="col-md-1">
              {{returnSet.return_products[0].order_product.order_id}}
            </div>
            <div class="col-lg-3 col-md-12">
              <div class="imgCartItem">
                <img v-if="returnSet.set.main_photo" :src="`/images/sets/og/${returnSet.set.main_photo.src}`">
                <img v-else src="/images/no-image.png" alt="">
              </div>
              <div class="cartDescr openSet">
                <p>{{ returnSet.set.translation.name }}</p>
              </div>
            </div>
            <div class="col-lg-2 col-6 justify-content-center ngh">
              <select class="form-control" v-model="returnSet.qty" @change="changeSetQty($event, returnSet.id)">
                  <option :value="qty" v-for="qty in returnSet.return_products[0].order_product.order_set.qty">{{ qty }}</option>
              </select>
            </div>
            <div class="col-md-2">
              {{ returnSet.return_products[0].order_product.actual_price * returnSet.qty}}
            </div>
          </div>
        </div>

        <span class="amount">Total: {{amount}}</span>
        <div class="col totalsBtn">
          <input type="button" name="remAllItems" class="form-control" value="Delete all" @click="removeAll(returnId)">
          <input type="button" class="form-control" value="Next" @click="getUserdata">
        </div>

      </div>
      <div class="empty-response" v-else>No Items</div>
    </div>

    <form @submit.prevent="checkUserdata" v-if="step == 2">

      <div class="col-12">
          <h4>User Data</h4>
      </div>

      <div class="col-12 adressUnlogged">
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="name">Name<b>*</b></label>
                  <input type="text" id="name" name="name" class="form-control"
                         v-model="name" v-validate="{required: true, min:3}">
                  <div v-if="errors.has('name')" class="invalid-feedback">{{ errors.first('name') }}</div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="name">Surname<b>*</b></label>
                  <input type="text" id="surname" name="surname" class="form-control"
                         v-model="surname" v-validate="{required: true, min:3}">
                  <div v-if="errors.has('surname')" class="invalid-feedback">{{ errors.first('surname') }}</div>
              </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Phone<b>*</b></label>
              <input type="text" id="phone" name="phone" class="form-control"
                     v-model="phone" v-validate="{required: true, min:9}">
              <div v-if="errors.has('phone')" class="invalid-feedback">{{ errors.first('phone') }}</div>
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="name">Email<b>*</b></label>
                  <input type="email" id="email" name="email" class="form-control"
                         v-model="email" v-validate="{required: true, email: true}">
                  <div v-if="errors.has('email')" class="invalid-feedback">{{ errors.first('email') }}</div>
              </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-5 col-6">
        <div class="btnGrey">
            <input type="submit" value="Submit">
        </div>
      </div>
    </form>

    <form @submit.prevent="checkDelivery" class="formModal" v-if="step == 3">
      <div class="modBuy">
        <div class="checkBoxesBuy">
          <label class="checkTerms" for="buttDelivery">Courier
            <input type="radio" name="delivery" id="buttDelivery" :checked="delivery == 'courier'" v-model="delivery" value="courier" >
            <span class="checkmarkTerms"></span>
          </label>
          <label class="checkTerms" for="buttPickup">Pickup
            <input type="radio" name="delivery" id="buttPickup" :checked="delivery == 'pickup'" v-model="delivery" value="pickup">
            <span class="checkmarkTerms"></span>
          </label>
        </div>

        <div class="deliveryBlock" id="deliveryBlock" v-if="delivery=='courier'" style="display: block;">
          <template v-if="userAddresses.length > 0">
            <select name="addressname" class="is-valid" id="addressname" @change="changeAddress" v-validate="{required: true}" v-model="addressname">
                <option v-for="address in userAddresses" :value="address.id">{{address.addressname}}</option>
            </select>
            <div v-if="errors.has('addressname')" class="invalid-feedback">{{ errors.first('addressname') }}</div>
          </template>
          <template v-else>
            <input type="text" name="addressname" placeholder="Address name" id="addressname" v-model="addressname" v-validate="{required: true}">
            <div v-if="errors.has('addressname')" class="invalid-feedback">{{ errors.first('addressname') }}</div>
          </template>

          <select name="country" class="name" id="country" v-validate="{required: true}" v-model="country">
              <option v-for="country in countries" :value="country.id">{{country.name}}</option>
          </select>
          <div v-if="errors.has('country')" class="invalid-feedback">{{ errors.first('country') }}</div>

          <input type="text" name="region" id="region" placeholder="Region"
                 v-model="region" v-validate="{required: true}">
          <div v-if="errors.has('region')" class="invalid-feedback">{{ errors.first('region') }}</div>

          <input type="text" name="location" id="location" placeholder="Location"
                 v-model="location" v-validate="{required: true}">
          <div v-if="errors.has('location')" class="invalid-feedback">{{ errors.first('location') }}</div>

          <input type="text" name="address" placeholder="Address" id="address" v-model="address" v-validate="{required: true}">
          <div v-if="errors.has('address')" class="invalid-feedback">{{ errors.first('address') }}</div>
        </div>

        <div class="pickupBlock" id="pickupBlock" v-else style="display: block;">
          <p>Pickup info</p>
          <select name="pickup" class="name is-valid" id="pickup" v-validate="{required: true}" v-model="pickupAddress">
              <option v-for="pickupAddress in pickup.translation_by_language" :key="pickupAddress.id" :value="pickupAddress.id">{{pickupAddress.value}}</option>
          </select>
          <div v-if="errors.has('addressname')" class="invalid-feedback">{{ errors.first('addressname') }}</div>
        </div>

      </div>
      <div class="buttHover">
        <input type="submit" class="butt" value="Next">
      </div>
    </form>

    <form @submit.prevent="returns" class="formModal" v-if="step == 4">
      <div class="modBuy">
          <label class="checkTerms" for="buttDelivery">Card
            <input type="radio" checked name="payment" id="buttDelivery" value="card" v-validate="{required: true}" v-model="payment">
            <span class="checkmarkTerms"></span>
          </label>
          <label class="checkTerms" for="buttPickup">Cash
            <input type="radio" name="payment" id="buttPickup" value="cash" v-validate="{required: true}" v-model="payment">
            <span class="checkmarkTerms"></span>
          </label>
          <div v-if="errors.has('payment')" class="invalid-feedback">{{ errors.first('payment') }}</div>
      </div>
      <div class="buttHover">
        <input type="submit" class="butt" value="Submit">
      </div>
    </form>

    <div class="loader-spiner" v-if="loading">
        <div class="lds-dual-ring"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['user_id', 'return_id', 'products', 'sets', 'userReturn'],
  data() {
    return {
      userId: this.userReturn !== undefined ? this.userReturn.user_id : this.user_id,
      name: null,
      surname: null,
      phone: null,
      email: null,
      returnId: this.userReturn !== undefined ? this.userReturn.id : this.return_id,
      returnProducts: this.products,
      returnSets: this.sets,
      step: 1,
      messages: null,
      delivery: this.userReturn !== undefined ? this.userReturn.delivery : 'courier',
      userAddresses: [],
      countries: [],
      createdAddress: null,
      addressname: null,
      country: null,
      region: null,
      location: null,
      address: null,
      pickup: [],
      pickupAddress: null,
      payment: this.userReturn !== undefined ? this.userReturn.payment : 'cash',
      loading: false
    }
  },
  created() {
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
  },
  watch: {
    country(newVal, oldVal) {
      if(this.userAddresses.length > 0) {
        const index = this.userAddresses.findIndex(address => address.id == this.addressname)
        this.userAddresses[index].country = newVal
      }
    },
    region(newVal, oldVal) {
      if(this.userAddresses.length > 0) {
        const index = this.userAddresses.findIndex(address => address.id == this.addressname)
        this.userAddresses[index].region = newVal
      }
    },
    location(newVal, oldVal) {
      if(this.userAddresses.length > 0) {
        const index = this.userAddresses.findIndex(address => address.id == this.addressname)
        this.userAddresses[index].location = newVal
      }
    },
    address(newVal, oldVal) {
      if(this.userAddresses.length > 0) {
        const index = this.userAddresses.findIndex(address => address.id == this.addressname)
        this.userAddresses[index].address = newVal
      }
    }
  },
  computed: {
    orderItemsLength() {
      return this.returnProducts.length + this.returnSets.length
    },
    getProductsAmount() {
      return this.returnProducts.reduce((acc, returnProduct) => {
        return acc + (returnProduct.order_product.actual_price * returnProduct.qty)
      }, 0)
    },
    getSetsAmount() {
      return this.returnSets.reduce((acc, returnSet) => {
        return acc + (returnSet.return_products[0].order_product.actual_price * returnSet.qty)
      }, 0)
    },
    amount() {
      return this.getProductsAmount + this.getSetsAmount
    }
  },
  methods: {
    // go back to previous step
    goBack() {
      this.step -= 1
    },
    // remove product from return
    async removeProduct(id) {
      try {
        const result = await axios.post('/back/returns/removeProduct', { id })
        this.returnProducts = this.returnProducts.filter(returnProduct => returnProduct.id !== id)
        this.$emit('remove', this.returnProducts, this.returnSets)
      } catch(err) {
        console.log(err)
      }
    },
    // change product qty in returnproduct
    async changeProductQty(e, id) {
      try {
        const result = await axios.post('/back/returns/changeProductQty', { id, qty: e.target.value })
      } catch(err) {
        console.log(err)
      }
    },
    // remove set from return
    async removeSet(id) {
      try {
        await axios.post('/back/returns/removeSet', { id })
        this.returnSets = this.returnSets.filter(returnSet => returnSet.id !== id)
        this.$emit('remove', this.returnProducts, this.returnSets)
      } catch(err) {
        console.log(err)
      }
    },
    // change set qty from returnset
    async changeSetQty(e, id) {
      try {
        await axios.post('/back/returns/changeSetQty', { id, qty: e.target.value })
      } catch(err) {
        console.log(err)
      }
    },
    // remove all items from return
    async removeAll(id) {
      try {
        await axios.post('/back/returns/removeAll', { id })
        this.returnProducts = []
        this.returnSets = []
        this.$emit('remove', this.returnProducts, this.returnSets)
      } catch(err) {
        console.log(err)
      }
    },
    // get userdata
    async getUserdata() {
      try {
        this.$emit('hideUsers')
        this.step = 2
        const result = await axios.post('/back/order/getUserdata', { id: this.userId })
        this.name = result.data.name
        this.surname = result.data.surname
        this.phone = result.data.phone
        this.email = result.data.email
      } catch(err) {
        console.log(err)
      }
    },
    // update userdata
    async checkUserdata() {
      try {
        this.step = 2
        let result = await this.$validator.validate()

        if(result) {
          result = await axios.post('/back/order/checkUserdata', {
            id: this.userId,
            name: this.name,
            surname: this.surname,
            phone: this.phone,
            email: this.email
          })

          this.getAddressdata()
          this.step = 3
        }
      } catch(err) {
        this.messages = err.response.data.errors
      }
    },
    // get addressdata
    async getAddressdata() {
      try {
        const result = await axios.post('/back/order/getAddressdata', {id: this.userId})

        if(result.data.addresses.length > 0) {
          this.userAddresses = result.data.addresses
          this.addressname = this.userAddresses[0].id
          this.country = parseInt(this.userAddresses[0].country)
          this.region = this.userAddresses[0].region
          this.location = this.userAddresses[0].location
          this.address = this.userAddresses[0].address
        } else {
          this.country = result.data.countries[0].id
        }

        this.countries = result.data.countries
        this.pickup = result.data.pickup
        this.pickupAddress = this.pickup.translation_by_language[0].id
      } catch(err) {
        console.log(err)
      }
    },
    // change address from select
    changeAddress(e) {
      const address = this.userAddresses.find(address => address.id == e.target.value)
      this.country = parseInt(address.country)
      this.region = address.region
      this.location = address.location
      this.address = address.address
    },
    // change delivery type
    checkDelivery() {
      if(this.delivery == 'pickup') {
        this.step = 4
      } else {
        this.checkUseraddress()
      }
    },
    // update user addresses or its not create new one
    async checkUseraddress() {
      try {
        let result = await this.$validator.validate()
        this.createdAddress = null

        if(result) {
          if(this.userAddresses.length == 0) {
            result = await axios.post('/back/order/address/create', {
              id: this.userId,
              addressname: this.addressname,
              country: this.country,
              region: this.region,
              location: this.location,
              address: this.address
            })

            this.createdAddress = result.data.address.id
            this.getAddressdata()
          } else {
            result = await axios.post('/back/order/address/update', {id: this.userId, addresses: this.userAddresses})
          }

          this.step = 4
        }
      } catch(err) {
        this.messages = err.response.data.errors
      }
    },
    // make return active with all selected options
    async returns() {
      try {
        let result = await this.$validator.validate()

        if(result) {
          let addressMain = null

          if(this.delivery === 'courier') {
            if(this.createdAddress) {
              addressMain = this.createdAddress
            } else {
              addressMain = this.addressname
            }
          } else {
            addressMain = this.pickupAddress
          }

          result = await axios.post('/back/returns/checkout', {
            userId: this.userId,
            returnId: this.returnId,
            addressMain,
            delivery: this.delivery,
            payment: this.payment
          })

          location.href = '/back/returns'
        }
      } catch(err) {
        this.serverErrors = err.response.data.errors
      }
    }
  }
}
</script>

<style scoped>
.invalid-feedback {
  display: block;
}
.is-valid {
  border-color: #28a745 !important;
}
.is-invalid {
  border-color: #dc3545 !important;
}
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
