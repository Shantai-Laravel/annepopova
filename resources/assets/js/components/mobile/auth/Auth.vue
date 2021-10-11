<template>
<div>
    <div class="modalTabs">
        <div id="registerTab" :class="showRegisterTab ? 'active' : ''" @click="deactivateActiveTab('registration')">{{ trans.vars.Auth.registrationTitle }}</div>
        <div id="guestTab" @click="showGuestTab" v-if="isGuest">{{ trans.vars.Auth.asGuest }}</div>
        <div id="loginTab" :class="showLoginTab ? 'active' : ''" @click="deactivateActiveTab('login')">{{ trans.vars.Auth.login }}</div>
    </div>
    <div class="modalBody">
        <div id="registerContent" class="active" v-if="showRegisterTab">
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.fieldFullName }}</label>
                <input type="text" v-model="registerUserData.name"/>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.fieldEmail }}</label>
                <input type="text" v-model="registerUserData.email"/>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.fieldphone }}</label>
                <div class="phoneContainer">
                    <div class="telefonGroup">
                        <div class="selectContainer">
                            <select class="js-example-basic-single_" name="" v-model="registerUserData.code">
                                <option :value="country.phone_code"
                                    :data-image="'/images/flags/16x16/' + country.flag"
                                    v-for="country in countriesBeforeClick">
                                    <span>+{{ country.phone_code }}</span>
                                </option>
                            </select>
                            <span>
                                <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                        <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero">
                                            <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                                <g id="Ship" transform="translate(15.000000, 108.000000)">
                                                    <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <input type="number" v-model="registerUserData.phone"/>
                </div>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.pass }}</label>
                <input type="password" v-model="registerUserData.password"/>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.passRepeat }}</label>
                <input type="password" v-model="registerUserData.passwordAgain"/>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.consumerType }}</label>
                <div class="selectContainer">
                    <select v-model="registerUserData.consumerType" @change="setConsumerType">
                        <option value="customer">{{ trans.vars.FormFields.consumer }}</option>
                        <option value="diller">{{ trans.vars.FormFields.diler }}</option>
                    </select>
                    <span>
                        <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero">
                                    <g id="Order-summery" transform="translate(1233.000000, 423.000000)">
                                        <g id="Ship" transform="translate(15.000000, 108.000000)">
                                            <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="inputGroup" v-if="registerUserData.companyField">
                <label for="name">{{ trans.vars.FormFields.company }}</label>
                <input type="text" v-model="registerUserData.company"/>
            </div>
            <label class="checkContainer">
            <input type="checkbox" name="terms" v-model="registerUserData.agree"/>{{ trans.vars.FormFields.termsUserAgreementPersonalData3 }} <a :href="'/' + $lang + '/terms'" target="_blank"> {{ trans.vars.PagesNames.pageNameTermsConditions }}</a>
            <span class="check"></span>
            </label>
            <p class="text-danger text-center" v-if="registerError">
                {{ registerError }}
            </p>
            <p class="text-danger text-center" v-for="(error, index) in serverErrors" :key="index" v-html="error">
                {{ error }}
            </p>
            <input type="submit" :value="trans.vars.Auth.registrationTitle" @click.prevent="register"/>
        </div>
        <div id="loginContent" class="active" v-if="showLoginTab">
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.fieldEmail }}</label>
                <input type="text" v-model="loginUserData.email"/>
            </div>
            <div class="inputGroup">
                <label for="name">{{ trans.vars.FormFields.pass }}</label>
                <input type="password" v-model="loginUserData.password"/>
            </div>
            <input type="submit" :value="trans.vars.Auth.login"  @click.prevent="login"/>
            <p class="text-danger text-center" v-if="loginError.length">
                {{ loginError }}
            </p>
            <p class="text-danger text-center" v-for="(error, index) in serverErrors" :key="index" v-html="error">
                {{ error }}
            </p>
            <div class="forg" data-target="#forgetPassword" data-toggle="modal" data-dismiss="modal">
                Forgot password?
            </div>
            <div class="loginBy">
                <p>Login with</p>
                <div class="dflex">
                    <div>
                        <a :href="'/' + $lang + '/login/facebook'">
                            <svg width="12px" height="20px" viewBox="0 0 14 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Sign_in._APJ_popoup---" transform="translate(-821.000000, -866.000000)" fill="#42261D" fill-rule="nonzero" > <g id="Sign-in" transform="translate(662.000000, 321.000000)"> <g id="Qiuck-register" transform="translate(107.000000, 496.000000)"> <g id="facebook-logo" transform="translate(52.000000, 49.000000)"> <path d="M13.4728011,0.00540973544 L10.1148377,0 C6.34228098,0 3.9042891,2.51146968 3.9042891,6.39863508 L3.9042891,9.34883431 L0.528007081,9.34883431 C0.23625623,9.34883431 0,9.58632169 0,9.87925887 L0,14.1537613 C0,14.4466985 0.236525621,14.6839154 0.528007081,14.6839154 L3.9042891,14.6839154 L3.9042891,25.4698459 C3.9042891,25.7627831 4.14054533,26 4.43229618,26 L8.83738382,26 C9.12913468,26 9.36539091,25.7625126 9.36539091,25.4698459 L9.36539091,14.6839154 L13.313052,14.6839154 C13.6048029,14.6839154 13.8410591,14.4466985 13.8410591,14.1537613 L13.8426754,9.87925887 C13.8426754,9.73860574 13.7869114,9.60390333 13.6880448,9.5043642 C13.5891782,9.40482507 13.4544825,9.34883431 13.314399,9.34883431 L9.36539091,9.34883431 L9.36539091,6.84791361 C9.36539091,5.6458704 9.65067636,5.03565224 11.210183,5.03565224 L13.4722623,5.03484078 C13.7637438,5.03484078 14,4.79735339 14,4.5046867 L14,0.535563809 C14,0.243167608 13.7640132,0.00595070899 13.4728011,0.00540973544 Z" id="Path" ></path> </g> </g> </g> </g> </g> </svg>
                        </a>
                    </div>
                    <div>
                        <a :href="'/' + $lang + '/login/google'">
                            <svg width="30px" height="20px" viewBox="0 0 37 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Sign_in._APJ_popoup---" transform="translate(-1088.000000, -870.000000)" fill="#42261D" fill-rule="nonzero" > <g id="Sign-in" transform="translate(662.000000, 321.000000)"> <g id="Qiuck-register" transform="translate(107.000000, 496.000000)"> <g id="google-plus" transform="translate(319.000000, 53.000000)"> <polygon id="Path" points="32.56 8.84210526 32.56 3.78947368 30.34 3.78947368 30.34 8.84210526 25.9 8.84210526 25.9 11.3684211 30.34 11.3684211 30.34 16.4210526 32.56 16.4210526 32.56 11.3684211 37 11.3684211 37 8.84210526" ></polygon> <path d="M11.7166667,9.6 L11.7166667,14.4 L18.3459567,14.4 C17.37816,17.1936 14.7723733,19.2 11.7166667,19.2 C7.84079333,19.2 4.68666667,15.9696 4.68666667,12 C4.68666667,8.0304 7.84079333,4.8 11.7166667,4.8 C13.3968367,4.8 15.0137367,5.4168 16.2697633,6.5376 L19.3489033,2.9184 C17.2399033,1.0368 14.53101,0 11.7166667,0 C5.25609667,0 0,5.3832 0,12 C0,18.6168 5.25609667,24 11.7166667,24 C18.1772367,24 23.4333333,18.6168 23.4333333,12 L23.4333333,9.6 L11.7166667,9.6 Z" id="Path" ></path> </g> </g> </g> </g> </g> </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import { bus } from '../../../app.js'

    export default {
        props: ['guest', 'site'],
        data() {
            return {
                registerUserData : {
                    email: '',
                    name: '',
                    code: '',
                    phone: '',
                    password: '',
                    passwordAgain: '',
                    agree: false,
                    consumerType: 'customer',
                    company: '',
                    companyField: false,
                },
                loginUserData: {
                    email: '',
                    password: '',
                },
                guestUserData: {
                    name: '',
                    phone: '',
                    email: '',
                    code: '',
                },
                countries: [],
                countriesBeforeClick: [],
                registerError: false,
                loginError: [],
                serverErrors: [],
                guestLoginError: [],
                isGuest: false,
                showLoginTab: true,
                showRegisterTab: false,
            }
        },
        mounted() {
            this.getCountriesList();
            bus.$on('setGuest', data => {
                this.isGuest = true;
            });
            if (this.guest.length > 0) {
                this.setGuestData();
            }
        },
        methods: {
            setCountriesList(){
                this.countriesBeforeClick = this.countries;
            },
            setGuestData(){
                let guest = JSON.parse(this.guest);
                this.guestUserData.name = guest.name;
                this.guestUserData.email = guest.email;
                this.guestUserData.phone = guest.phone;
            },
            getCountriesList(){
                axios.post('/' + this.$lang +'/'+ this.site + '/auth-get-phone-codes-list')
                    .then(response => {
                        this.registerUserData.code = response.data.currentCountry.phone_code;
                        this.countries = response.data.countries;
                    })
                    .catch(e => { console.log('error load codes') });
            },
            register(){
                if (this.validateRegistration() == true) {
                    axios.post('/' + this.$lang +'/'+ this.site + '/registration', this.registerUserData)
                        .then(response => {
                            if (response.data.status === 'false') {
                                this.serverErrors = response.data.errors
                            }else{
                                window.location.reload(true);
                            }
                        })
                        .catch(e => { console.log('error user register') });
                }
            },
            login(){
                if (this.validateLogin() == true) {
                    axios.post('/' + this.$lang +'/'+ this.site + '/auth-login', this.loginUserData)
                        .then(response => {
                            if (response.data.status === 'false') {
                                this.serverErrors = response.data.errors
                            }else{
                                window.location.reload(true);
                            }
                        })
                        .catch(e => { console.log('error user login') });
                }
            },
            signGuestUser(){
                axios.post('/' + this.$lang +'/'+ this.site + '/auth-guest-login', this.guestUserData)
                    .then(response => {
                        if (response.data.status === 'false') {
                            this.serverErrors = response.data.errors
                        }else{
                            window.location.href = window.location.origin + '/' + this.$lang + '/order';
                        }
                    })
                    .catch(e => { console.log('error user login') });

            },
            validateLogin(){
                this.loginError = [];
                if (this.loginUserData.email.length < 2) {
                    this.loginError = trans.vars.Notifications.emailRequired;
                    return false;
                }
                if (!this.validateEmail(this.loginUserData.email)) {
                    this.loginError = trans.vars.Notifications.emailNotValid
                    return false;
                }
                if (this.loginUserData.password.length < 2) {
                    this.loginError = trans.vars.Notifications.passNotValid
                    return false;
                }

                return true;
            },
            validateGuestLogin(){
                this.loginError = [];
                if (this.guestUserData.name.length < 2) {
                    this.guestLoginError = trans.vars.Notifications.nameRequired;
                    return false;
                }
                if (this.guestUserData.email.length < 2) {
                    this.guestLoginError = trans.vars.Notifications.emailRequired;
                    return false;
                }
                if (!this.validateEmail(this.guestUserData.email)) {
                    this.guestLoginError = trans.vars.Notifications.emailNotValid;
                    return false;
                }
                if (this.guestUserData.phone.length < 2) {
                    this.guestLoginError = trans.vars.Notifications.phoneRequired;
                    return false;
                }

                return true;
            },
            validateRegistration(){
                this.registerError = [];
                if (this.registerUserData.name.length < 2) {
                    this.registerError = trans.vars.Notifications.nameRequired;
                    return false;
                }
                if (this.registerUserData.email.length < 2) {
                    this.registerError = trans.vars.Notifications.emailRequired;
                    return false;
                }
                if (!this.validateEmail(this.registerUserData.email)) {
                    this.registerError = trans.vars.Notifications.emailNotValid;
                    return false;
                }
                // if (this.registerUserData.code.length < 1) {
                //     this.registerError = trans.vars.Notifications.codeRequired;
                //     return false;
                // }
                if (this.registerUserData.phone.length < 1) {
                    this.registerError = trans.vars.Notifications.phoneRequired;
                    return false;
                }

                if (this.registerUserData.password.length < 1) {
                    this.registerError = 'password missing';
                    return false;
                }
                if (this.registerUserData.password) {
                    if (!this.checkPwd(this.registerUserData.password)) {
                        this.registerError = 'Password must contain both numeric and alphabetic characters';
                        return false;
                    }
                }
                if (this.registerUserData.password !== this.registerUserData.passwordAgain) {
                    this.registerError = trans.vars.Notifications.passNotMatch;
                    return false;
                }

                if (this.registerUserData.agree == false) {
                    this.registerError = trans.vars.Notifications.agreeRequired;
                    return false;
                }

                return true;
            },
            checkPwd(str) {
                if (str.search(/\d/) == -1) {
                    return false;
                } else if (str.search(/[a-zA-Z]/) == -1) {
                    return false;
                } else if (str.search(/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\_\+]/) != -1) {
                    return false;
                }

                return true;
            },
            validateEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },
            showGuestTab(){
                $('.headTab').removeClass('active');
                $('#guestTab').addClass('active');
                this.signGuestUser();
                // $('.tabBloc').removeClass('activeBloc');
                // $('.guestBloc').addClass('activeBloc');
            },
            deactivateActiveTab(tab){
                this.setCountriesList();
                $('#guestTab').removeClass('active');
                $('.tabOpen').removeClass('active');

                if (tab == 'login') {
                    this.showRegisterTab = false;
                    this.showLoginTab = true;
                }else{
                    this.showRegisterTab = true;
                    this.showLoginTab = false;
                }
            },
            setConsumerType(){
                if (this.registerUserData.consumerType == 'diller') {
                    this.registerUserData.companyField = true;
                }else{
                    this.registerUserData.companyField = false;
                    this.registerUserData.company = '';
                }
            }
        },
    }
</script>

<style media="screen">
    .guestBloc{
        display: none;
    }

    .modal .modal-content {
      border-radius: 0;
    }

    .modal .modal-dialog {
      margin-top: 10rem;
      max-width: 490px;
    }

    .modal .modalContent {
      padding: 100px 80px;
      padding-bottom: 50px;
      padding-top: 0;
      background-image: url(/fronts/img/backgrounds/modal.jpg);
      background-size: cover;
      border: 1px solid #42261D;
    }

    .modal .modalTitle {
      font-family: "GillSans-Light";
      font-size: 18px;
      color: #B22D00;
      letter-spacing: 0;
      text-align: center;
      line-height: 18px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .modal label,
    .modal input {
      font-family: "GillSans-Light";
      font-size: 16px;
      color: #42261D;
      letter-spacing: 0;
      margin-bottom: 0;
    }

    .modal input {
      line-height: 40px;
      border: 1px solid #42261D;
      background-color: transparent;
      width: 100%;
      padding-left: 15px;
    }

    .modal input:focus {
      background-color: #fff;
      border-color: #42261D;
    }

    .modal .inputGroup {
      margin-bottom: 20px;
    }

    .modal .phoneContainer {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      border: 1px solid #42261D;
    }

    .modal .phoneContainer select {
      width: 120px;
      border-radius: 0;
      background-color: #edcac1;
    }

    .modal .phoneContainer .selectContainer {
      border: 0;
      margin: 0;
    }

    .modal .phoneContainer input {
      border: 0;
    }

    .modal .phoneContainer img {
      width: 25px;
      margin-right: 10px;
    }

    .modal .phoneContainer svg {
      margin-left: 10px;
    }

    .modal input[type="submit"] {
      font-family: "GillSans-Light";
      font-size: 24px;
      color: #B22D00;
      letter-spacing: -0.03px;
      text-align: center;
      width: 100%;
      line-height: 50px;
      background-color: #FFE7CB;
      background-image: url(/fronts/img/backgrounds/buttons.jpg);
      background-size: 100% 100%;
      border: 1px solid #FFFFFF;
      text-transform: uppercase;
      -webkit-transition: font-size .5s ease;
      transition: font-size .5s ease;
      height: 50px;
    }

    .modal input[type="submit"]:active {
      font-size: 16px;
    }

    .modal .closeModal {
      width: 30px;
      height: 30px;
      margin-left: auto;
      margin-right: 0;
      margin-top: 10px;
      text-align: center;
      cursor: pointer;
      position: relative;
      right: -70px;
    }

    .modal .closeModal img {
      -webkit-transform: rotate(45deg);
              transform: rotate(45deg);
      width: 25px;
      margin: auto;
    }

    .modal .modalTabs {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      margin-bottom: 30px;
    }

    .modal .modalTabs div {
      width: 100%;
      font-family: "GillSans-Light";
      font-size: 18px;
      color: #42261D;
      letter-spacing: -0.25px;
      text-align: center;
      padding-top: 3px;
      cursor: pointer;
      line-height: 30px;
    }

    .modal .modalTabs .active {
      border-bottom: 1px solid #42261D;
      font-family: "GillSans";
    }

    .modal .checkContainer {
      margin: 20px 0;
    }

    .modal .checkContainer .check {
      top: 0;
    }

    .modal .loginBy {
      margin-top: 40px;
    }

    .modal .loginBy p {
      font-family: "GillSans-Light";
      font-size: 16px;
      color: #4F4F4F;
      letter-spacing: 0;
      text-align: center;
      margin-bottom: 10px;
    }

    .modal .loginBy .dflex {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-pack: justify;
          -ms-flex-pack: justify;
              justify-content: space-between;
    }

    .modal .loginBy .dflex div {
      float: left;
      border: 1px solid #42261D;
      border-radius: 26px;
      height: 40px;
      width: 100px;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-pack: center;
          -ms-flex-pack: center;
              justify-content: center;
      -webkit-box-align: center;
          -ms-flex-align: center;
              align-items: center;
      cursor: pointer;
    }

    .modal .forg {
      font-family: "GillSans-Light";
      font-size: 16px;
      color: #4F4F4F;
      letter-spacing: 0;
      text-decoration: underline;
      margin-top: 30px;
      cursor: pointer;
    }

    .modal #registerContent,
    .modal #loginContent {
      display: none;
    }

    .modal #registerContent.active,
    .modal #loginContent.active {
      display: block;
    }
</style>
