<template>

    <tr class="row100">
        <td class="column-id">new</td>
    	<td class="column-id">
            <input type="text" :value="lang.lang.toUpperCase()" disabled v-for="lang in langs">
        </td>
       <td class="column-text">
            <input type="text" v-model="product.name[lang.id]" placeholder="---" v-for="(lang, index) in langs" @keyup="setChange">
        </td>
        <td class="column-number">
            ---
        </td>
        <td class="column-select">
            ---
        </td>
        <td class="column-number">
            <input type="text" v-model="product.code" placeholder="---">
        </td>
        <!-- <td class="column-number">
            <input type="text" v-model="product.ean_code" placeholder="-">
        </td> -->
        <td class="column-select column-button" v-for="param in category.params" v-if="dependeble !== param.property.id">
            <select v-model="properties[param.property.id]" v-if="param.property.type === 'select'">
                <option value="0">---</option>
                <option :value="mutidata.id" v-for="mutidata in param.property.parameter_values">{{ mutidata.translation.name }}</option>
            </select>

            <select multiple v-model="propertiesCheckbox[param.property.id]" v-if="param.property.type === 'checkbox'">
                <option value="0">---</option>
                <option :value="mutidata.id" v-for="mutidata in param.property.parameter_values">{{ mutidata.translation.name }}</option>
            </select>

            <input type="text" v-model="propertiesText[param.property.id][key]" v-for="(propertyText, key) in propertiesText[param.property.id]" v-if="param.property.type === 'textarea' || param.property.type === 'text'">
        </td>
        <!-- <td class="column-number">
            <input type="number" v-model="product.stoc" placeholder="-">
        </td> -->
        <!-- <td class="column-number">
            <input type="number" v-model="product.discount" placeholder="-">
        </td> -->
        <td class="column-select">
            <select v-model="product.promotion">
                <option value="0">---</option>
                <option :value="promotion.id" v-for="promotion in promotions">{{ promotion.translation.name }}</option>
            </select>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>prices can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>images can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>images can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>video can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>subproducts can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>outbreaks can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>sets can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>collection can be added only after saving!</small></p>
        </td>
        <td class="column-button">
            <p class="text-warning"><small>similar products can be added only after saving!</small></p>
        </td>
    	<!-- <td class="column-number">
            <input type="number" v-model="product.price" placeholder="-">
        </td> -->

        <td class="column-text">
            <input type="text" v-model="product.description[lang.id]" placeholder="---" v-for="(lang, index) in langs">
        </td>
    	<td class="column-text">
            <input type="text" v-model="product.body[lang.id]" placeholder="---" v-for="(lang, index) in langs">
        </td>
        <td class="column-text">
            <input type="text" v-model="product.atributes[lang.id]" placeholder="---" v-for="(lang, index) in langs">
        </td>
    </tr>

</template>

<script>
    import { bus } from '../../app';

    export default {
        props: ['category', 'langs', 'promotions'],
        data(){
            return {
                product : {
                    name : [],
                    description : [],
                    body : [],
                    atributes: [],
                    code : '',
                    ean_code: '',
                    price : 0,
                    stoc : 0,
                    discount : 0,
                    promotion : 0,
                },
                properties : [],
                propertiesCheckbox: [],
                propertiesText : [],
                dependeble: this.category.property ? this.category.property.parameter_id : 0,
            }
        },
        mounted(){
            this.getPropValue();
            this.getPropValueText();
            this.setDefaultValues();
            bus.$on('create', data => {
                this.save();
            })
        },
        methods: {
            setChange(e){
                if (e.target.value) {
                    bus.$emit('documentChange', 'new');
                }
            },
            save(){
                bus.$emit('startLoading');
                axios.post('/back/auto-upload-create', {product: this.product, properties: this.properties, propertiesText: this.propertiesText, propertiesCheckbox: this.propertiesCheckbox, category_id: this.category.id})
                    .then(response => {
                        bus.$emit('updatePage');
                        this.setDefaultValues();
                        this.getPropValueText();
                        this.getPropValue();
                        this.product.code = '';
                        this.product.ean_code = '';
                        this.product.stoc = 0;
                        this.product.price = 0;
                        this.product.discount = 0;
                        this.product.promotion = 0;
                        bus.$emit('clearSearch');
                        bus.$emit('endLoading');
                    })
                    .catch(e => {
                        console.log('error load products');
                    })
            },
            setDefaultValues(){
                let defaultValsName = [];
                let defaultValsDesc = [];
                let defaultValsBody = [];

                this.langs.forEach(function(entry){
                    defaultValsName[entry.id] = '';
                    defaultValsDesc[entry.id] = '';
                    defaultValsBody[entry.id] = '';
                });

                this.product.name = defaultValsName;
                this.product.description = defaultValsDesc;
                this.product.body = defaultValsBody;
            },
            getPropValue(){
                let vm = this;
                let defaultVal = [];
                let defaultValCheckbox = [];

                 this.category.params.forEach(function (entry) {
                     if (vm.dependeble !== entry.property.id) {
                         if (entry.property.type == 'select') {
                             defaultVal[entry.parameter_id] = 0;
                        }else if(entry.property.type == 'select'){
                            defaultValCheckbox[entry.parameter_id] = [];
                        }
                     }
                 })

                 this.propertiesCheckbox = defaultValCheckbox;
                 this.properties = defaultVal;
            },
            getPropValueText(){
                let vm = this;
                let ret = [];
                let defaultVal = [];

                 this.category.params.forEach(function (entry) {
                     if (entry.property.type == 'textarea' ||  entry.property.type == 'text') {
                        if (entry.property.multilingual == 1) {
                            vm.langs.forEach(function(item, key){
                                ret[key] = '';
                                return ret;
                            })
                        }else{
                            ret[0] = '';
                        }
                        defaultVal[entry.parameter_id] = ret;
                        ret = [];
                     }
                 })
                 this.propertiesText = defaultVal;
            },
        },
    }
</script>
