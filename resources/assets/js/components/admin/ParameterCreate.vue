<template>

    <div class="part full-part">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="key">Type</label>
                <select class="form-control" name="" v-model="type">
                    <option value="0">Choose a type</option>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="select">Select</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="key">Key (unique)</label>
                <input type="text" name="key" v-model="key" class="form-control">
            </div>
            <div class="col-md-4 form-group">
                <label for="key">Group</label>
                <select class="form-control" v-model="groupId">
                    <option value="0">----</option>
                    <option :value="group.id" v-for="group in groups">{{ group.key }}</option>
                </select>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="filter" v-model="filter">
                    <span>In Filter</span>
                </label>
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="multilingualTitle" v-model="multilingualTitle" @change="setMultilingualTitle">
                    <span>Multiling Title</span>
                </label>
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="multilingualUnit" v-model="multilingualUnit" @change="setMultilingualUnit">
                    <span>Multiling Unit</span>
                </label>
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="multilingual" v-model="multilingual" @change="setMultilingual">
                    <span>Multiling Values</span>
                </label>
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="multilingual" v-model="main">
                    <span>Main</span>
                </label>
            </div>
        </div>

        <div class="row" v-if="type == 'text' ||type ==  'textarea'">

            <div class="parent col-md-12">
                <div class="child col" v-for="language in languagesTitle">
                    <div class="form-group">
                        <span>Name [{{ language.lang }}]</span>
                        <input type="text" v-model="name[language.id]" class="form-control">
                    </div>
                </div>
            </div>
            <div class="parent col-md-12">
                <div class="child col" v-for="language in languagesUnit">
                    <div class="form-group">
                        <span>Unit [{{ language.lang }}]</span>
                        <input type="text" v-model="unit[language.id]" class="form-control" placeholder="---">
                    </div>
                </div>
            </div>
        </div>

        <div class="row container" v-if="type == 'checkbox' ||  type ==  'select'">
            <div class="parent col-md-12">
                <div class="child col" v-for="language in languagesTitle">
                    <div class="form-group">
                        <span>Name [{{ language.lang }}]</span>
                        <input type="text" v-model="name[language.id]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="parent col-md-12">
                <div class="child col" v-for="language in languagesUnit">
                    <div class="form-group">
                        <span>Unit [{{ language.lang }}]</span>
                        <input type="text" v-model="unit[language.id]" class="form-control" placeholder="---">
                    </div>
                </div>
            </div>


            <div class="break">
                Parameter values:
            </div>
            <div class="parent col-md-12" v-for="(count, index) in parametersValues">
                <div class="child col" v-for="language in languages">
                    <div class="form-group">
                        <span>Parameter Value # {{ index + 1 }} [{{ language.lang }}]</span>
                        <input type="text" class="form-control" v-model="parametersValues[index][language.id]">
                        <i class="fa fa-trash" @click="removeNewValue(index)"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-center">
                <i class="fa fa-plus add-button" @click="addNewRow"></i>
            </div>
        </div>

        <div class="row text-center">
            <div class="text-danger" v-if="errorMesage">
                {{ errorMesage }} <hr>
            </div>
            <button type="button" class="btn btn-primary save-btn" @click="validateForm">Save</button>
            <button type="button" class="btn btn-primary" @click="openCategoryArea">Add to category</button>
        </div>

        <!-- Categories -->
        <div class="categories-area" v-if="showCategories">
            <div> <i @click="closeCategoryArea" class="fa fa-close"></i> </div>
            <h5 class="text-center">Categories</h5>
            <ul class="cats-tree">
                <li>
                    <label>
                        <input type="checkbox" name="filter" class="checkbox" @change="checkAll">
                        <span>{{ checkStatus ? 'Check all' : 'Uncheck all'}} </span>
                    </label>
                </li>
                <li v-for="category in categories">
                    <label>
                        <input type="checkbox" v-model="checkedCategories[category.id]" class="checkbox" @change="checkCategory(category.id)">
                        <span>{{ category.translation.name }}</span>
                    </label>
                    <ul>
                        <li v-for="children in category.children" v-if="category.children.length !== 0">
                            <label>
                                <input type="checkbox" v-model="checkedCategories[children.id]" class="checkbox" @change="checkCategory(children.id)">
                                <span>{{ children.translation.name }}</span>
                            </label>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>

</template>

<script>
import { bus } from '../../app';

export default {
    props: ['langs', 'lang', 'categories', 'groups'],
    data(){
        return {
            type: 0,
            key: '',
            filter: '',
            multilingual: false,
            multilingualTitle: false,
            multilingualUnit: false,
            languages : [this.lang],
            languagesTitle: [this.lang],
            languagesUnit: [this.lang],
            main: false,
            validate: false,
            errorMesage: '',
            name: [],
            unit: [],
            parametersValues: [],
            countValues: 0,
            showCategories: false,
            checkedCategories: [],
            checkStatus: true,
            groupId: 0,
        }
    },
    created(){
        this.setDefaultValues();
        console.log(this.categories);
    },
    methods: {
        setMultilingual(){
            if (this.languages.length == 1) {
                this.languages = this.langs;
            }else {
                this.languages = [this.lang];
            }
            // this.setDefaultValues();
        },
        setMultilingualTitle(){
            if (this.languagesTitle.length == 1) {
                this.languagesTitle = this.langs;
            }else {
                this.languagesTitle = [this.lang];
            }
            // this.setDefaultValues();
        },
        setMultilingualUnit(){
            if (this.languagesUnit.length == 1) {
                this.languagesUnit = this.langs;
            }else {
                this.languagesUnit = [this.lang];
            }
            // this.setDefaultValues();
        },
        validateForm(){
            this.errorMesage = '';
            if (this.type !== 0) {
                if (this.key.length > 0) {
                    if (this.name.length > 0) {
                        this.save();
                    }else {
                        this.errorMesage = "name cant be empty.";
                    }
                }else {
                    this.errorMesage = "key field cant be empty.";
                }
            }else{
                this.errorMesage = "choose the parameter type.";
            }
        },
        save(){
            axios.post('/back/parameter-store',{
                                                type: this.type,
                                                key: this.key,
                                                filter: this.filter,
                                                multilingual: this.multilingual,
                                                multilingualTitle: this.multilingualTitle,
                                                multilingualUnit: this.multilingualUnit,
                                                main: this.main,
                                                name: this.name,
                                                unit: this.unit,
                                                langs: this.langs,
                                                paramValues: this.parametersValues,
                                                categories: this.checkedCategories,
                                                groupId: this.groupId,
                                            })
                .then(response => {
                    if (response.data == true) {
                        window.location.href = "/back/parameters";
                    }
            })
        },
        setDefaultValues(){
            let vm = this;
            this.languages.forEach(function(lang){
                vm.name[lang.id] = '';
                vm.unit[lang.id] = '';
            });
        },
        setParametersValues(){
            let vm = this;
            let ret = [];

            for (var i = 0; i <= this.countValues; i++) {
                vm.languages.forEach(function(lang){
                    if (i === vm.countValues) {
                        ret[lang.id] = '';
                        vm.parametersValues[i] = ret;
                    }
                });
            }
        },
        addNewRow(){
            let vm = this;
            let ret = [];
            let defalutVal = this.parametersValues;
            this.parametersValues = [];

            vm.languages.forEach(function(lang){
                ret[lang.id] = '';
            });
            defalutVal.push(ret);
            this.parametersValues = defalutVal;
        },
        removeNewValue(id){
            if (confirm('Do you want to delete this value?')) {
                delete this.parametersValues.splice(id, 1);
            }
        },
        openCategoryArea(){
            this.showCategories = true;
        },
        closeCategoryArea(){
            this.showCategories = false;
        },
        checkAll(){
            let vm = this;
            let ret = [];
            this.categories.forEach(function(entry){
                ret[entry.id] = vm.checkStatus;
                if (entry.children.length) {
                    entry.children.forEach(function(child){
                        ret[child.id] = vm.checkStatus;
                    });
                }
            });
            this.checkedCategories = ret;
            this.checkStatus = this.checkStatus ? false : true;
        },
        checkCategory(id){
            let vm = this;
            let ret = this.checkedCategories;
            this.categories.forEach(function(entry){
                if (id == entry.id) {
                    if (entry.children.length) {
                        entry.children.forEach(function(child){
                            ret[child.id] = vm.checkedCategories[entry.id];
                        });
                    }
                }
            });
            this.checkedCategories = ret;
        }
    },
}
</script>

<style>
    .container{
        padding-right: 30px;
    }
    .parent {
        display: flex;
        overflow: scroll;
    }
    .child {
        min-height: 30px;
        border-left: 1px solid #85CE36;
        padding-left: 30px !important;
    }
    .col{
        flex: 2;
        min-width: 200px;
    }
    .lang{
        border: 1px solid #85CE36;
        color: #85CE36;
        text-transform: uppercase;
        padding: 5px;
        margin-bottom: 30px;
    }
    .child .form-group span{
        font-size: 10px;
        font-style: italic;
        display: block;
        text-align: right;
    }
    .child .form-group i{
        position: absolute;
        left: 26px;
        top: 24px;
        cursor: pointer;
    }
    .add-button{
        font-size: 18px;
        margin-bottom: 20px;
        cursor: pointer;
        transition: all .2s ease-in-out;
    }
    .add-button:hover{
        transform: scale(1.2);
    }
    .break{
        text-align: center;
        border-bottom: 1px solid #85CE36;
        margin-bottom: 30px;
    }
    .save-btn{
        margin-left: -43px;
    }
    .categories-area{
        width: 40%;
        height: calc(100vh - 70px);
        position: absolute;
        top: 75px;
        right: 0;
        background-color: #FFF;
        padding: 20px;
        box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        overflow: scroll;
    }
    .categories-area ul li ul{
        padding-left: 25px;
    }
</style>
