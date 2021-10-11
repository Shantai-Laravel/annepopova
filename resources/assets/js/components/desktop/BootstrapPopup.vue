<template>

    <div>
        <div class="modal-body"></div>
        <div class="modal-footer">
            <p>{{ trans.vars.Notifications.bootstrapSettingsRow3 }} <a :href="'/' + $lang + '/cookie'">{{ trans.vars.PagesNames.pageNameCookiePolicy }}</a> </p>
            <button type="button" class="btn btn-primary" @click="save">{{ trans.vars.FormFields.accept }}</button>
        </div>
    </div>

</template>

<script>


export default {
    data(){
        return {
            countries: [],
            languages: [],
            currencies: [],
            currentCountry: '',
            selectedCountry: 0,
            selectedLang: 0,
            currentCurrency: '',
        }
    },
    mounted(){
        this.getCountriesList();
    },
    methods: {
        getCountriesList(){
            axios.post('/' + this.$lang + '/bootsrap-get-countries-list')
                .then(response => {
                    console.log(response.data.currencies);
                    this.currentCountry = response.data.selected;
                    this.selectedCountry = response.data.selected.id;
                    this.selectedLang = response.data.selected.lang_id;
                    this.countries = response.data.countries;
                    this.languages = response.data.languages;
                    this.currencies = response.data.currencies;
                    this.currentCurrency = response.data.selected.currency.id;
                })
                .catch(e => { console.log('error load countries') });
        },
        save(){
            axios.post('/' + this.$lang + '/save-country-user', {countryId: this.selectedCountry, langId: this.selectedLang, currencyId: this.currentCurrency})
                .then(response => {
                    window.location.href = window.location.origin + '/' + response.data;
                })
                .catch(e => { console.log('error load countries') });
        },
    },
}

</script>


<style>
    .select2-container{
        width: 100% !important;
    }
    .select2-container img{
        width: 16px;
        height: 16px;
    }
    .select2-search--dropdown{
        width: 100% !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field{
        height: 30px;
    }
</style>
