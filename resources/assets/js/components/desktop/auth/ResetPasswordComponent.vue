<template>
<form class="modalContent">
    <div class="closeModal" data-dismiss="modal">
        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
    </div>
    <div class="modalTitle">
        {{ trans.vars.Auth.passForgotQuestion }}
    </div>
    <div class="modalBody" v-if="step == 1">
        <p>
            {{ trans.vars.Auth.forgetPassMessage }}
        </p>
        <div class="inputGroup">
            <label for="name">Email</label>
            <input type="text" v-model="email"/>
        </div>
        <p class="text-danger text-center" v-if="error" v-html="error"></p>
        <input type="submit" value="Send" @click.prevent="sendEmail"/>
    </div>
    <div class="modalBody" v-if="step == 2">
        <p>
            Introduceti mai jos codul primit in email.
        </p>
        <div class="inputGroup">
            <label for="name">Code</label>
            <input type="text" placeholder="Introduceti codul" v-model="code"/>
        </div>
        <p class="text-danger text-center" v-if="error" v-html="error"></p>
        <input type="submit" value="Send" @click.prevent="sendCode"/>
    </div>
    <div class="modalBody"  v-if="step == 3">
        <div class="inputGroup">
            <label for="password">New Password</label>
            <input type="password" placeholder="Parola noua" v-model="password"/>
        </div>
        <div class="inputGroup">
            <label for="password">New Password Again</label>
            <input type="password" placeholder="Repeta parola" v-model="passwordAgain"/>
        </div>
        <p class="text-danger text-center" v-if="error" v-html="error"></p>
        <input type="submit" value="Save" @click.prevent="resetPassword"/>
    </div>
</form>
</template>

<script>
    export default {
      data() {
            return {
                email: null,
                code: null,
                password: null,
                passwordAgain: null,
                token: document.querySelector('meta[name="_token"]').content,
                error: false,
                step: 1,
                site: 'homewear',
            }
      },
      methods: {
          sendEmail(){
              this.error = false;
              if (this.validateEmail(this.email) ) {
                  axios.post('/' + this.$lang + '/' + this.site + '/reset-password-send-email', {email: this.email})
                      .then(response => {
                          if (response.data.status == 'false') {
                              this.error = response.data.error.toString()
                          }else{
                              this.step = 2;
                              this.error = false;
                          }
                      })
                      .catch(e => { console.log('error reset password send email') });
              }else{
                  this.error = 'Email is not valid';
              }
          },
          sendCode(){
              if (this.code.length > 1 ) {
                  axios.post('/' + this.$lang + '/' + this.site + '/reset-password-send-code', {code: this.code})
                      .then(response => {
                          if (response.data.status == 'false') {
                              this.error = response.data.error.toString()
                          }else{
                              this.step = 3;
                              this.error = false;
                          }
                      })
                      .catch(e => { console.log('error reset password send code') });
              }else{
                  this.error = 'Code is not required';
              }
          },
          resetPassword(){
              if (this.validatePasswords()) {
                  axios.post('/' + this.$lang + '/' + this.site + '/reset-password-send-password', {password: this.password, _token: this.token})
                      .then(response => {
                          if (response.data.status == 'false') {
                              this.error = response.data.error
                          }else{
                              window.location.reload(true);
                          }
                      })
                      .catch(e => { console.log('error reset password') });
              }
          },
            validateEmail(email) {
               var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
               return re.test(String(email).toLowerCase());
            },
            validatePasswords(){
               if (this.password.length < 1) {
                   this.error = 'Password is required';
                   return false;
               }

               if (this.password !== this.passwordAgain) {
                   this.error = 'Passwords not much';
                   return false;
               }

               return true;
            },
        }
    }
</script>
