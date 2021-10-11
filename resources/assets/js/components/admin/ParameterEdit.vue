<template>

    <div class="part full-part">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="key">Type</label>
                <select class="form-control" v-model="type">
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
                <select class="form-control" v-model="groupId=item.group_id">
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
                    <input class="checkbox" type="checkbox" name="multilingual" v-model="multilingualTitle" @change="setMultilingualTitle">
                    <span>Multiling Title</span>
                </label>
            </div>
            <div class="col-md-2 form-group"><hr>
                <label>
                    <input class="checkbox" type="checkbox" name="multilingual" v-model="multilingualUnit" @change="setMultilingualUnit">
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

        <div class="row container" v-if="type == 'text' ||type ==  'textarea'">

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
            <!-- old value -->
            <div class="parent col-md-12" v-for="(paramValues, index) in item.parameter_values">
                <div class="child col" v-for="(language) in languages">
                    <div class="form-group">
                        <span>Parameter Value # {{ index + 1 }} [{{ language.lang }}]</span>
                        <input type="text" class="form-control" v-model="oldPropValues[paramValues.id][language.id]">
                        <i class="fa fa-trash" @click="removeOldValue(paramValues.id)"></i>
                    </div>
                </div>
                <div class="child col">
                    <div class="form-group">
                        <span>Subproduct Suffix# {{ index + 1 }}</span>
                        <input type="text" class="form-control" v-model="oldPropSuffix[paramValues.id]">
                        <i class="fa fa-trash" @click="removeOldValue(paramValues.id)"></i>
                    </div>
                </div>
            </div>

            <!-- new values -->
            <div class="parent col-md-12" v-for="(count, key) in parametersValues">
                <div class="child col" v-for="(language, index) in languages">
                    <div class="form-group">
                        <span>Parameter Value # {{ parameter.parameter_values.length + key + 1}} [{{ language.lang }}]</span>
                        <input type="text" class="form-control" v-model="parametersValues[key][language.id]">
                        <i class="fa fa-trash" @click="removeNewValue(key)"></i>
                    </div>
                </div>
                <div class="child col">
                    <div class="form-group">
                        <span>Subproduct Suffix#</span>
                        <input type="text" class="form-control" v-model="propSuffix[key]">
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
                            <ul>
                                <li v-for="children2 in children.children" v-if="children.children.length !== 0">
                                    <label>
                                        <input type="checkbox" v-model="checkedCategories[children2.id]" class="checkbox" @change="checkCategory(children2.id)">
                                        <span>{{ children2.translation.name }}</span>
                                    </label>
                                    <ul>
                                        <li v-for="children3 in children2.children" v-if="children2.children.length !== 0">
                                            <label>
                                                <input type="checkbox" v-model="checkedCategories[children3.id]" class="checkbox" @change="checkCategory(children.id)">
                                                <span>{{ children3.translation.name }}</span>
                                            </label>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
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
    props: ['langs', 'lang', 'parameter', 'categories', 'groups'],
    data(){
        return {
            item: this.parameter,
            type: this.parameter.type,
            key: this.parameter.key,
            filter: this.parameter.in_filter == 1 ? true : false,
            main: this.parameter.main == 1 ? true : false,

            multilingual: this.parameter.multilingual == 1 ? true : false,
            multilingualTitle: this.parameter.multilingual_title == 1 ? true : false,
            multilingualUnit: this.parameter.multilingual_unit == 1 ? true : false,

            languages : [this.lang],
            languagesTitle: [this.lang],
            languagesUnit: [this.lang],

            validate: false,
            errorMesage: '',
            name: [],
            unit: [],
            parametersValues: [],
            countValues: 0,
            oldPropValues: [],
            showCategories: false,
            checkedCategories: [],
            checkStatus: true,

            oldPropSuffix: [],
            propSuffix: [],

            groupId: 0,
        }
    },
    created(){
        this.getOldParametersValues();
        this.setDefaultValues();
        this.setCategoriesDefaultValues();

        this.languages = this.multilingual == true ? this.langs : [this.lang];
        this.languagesTitle = this.multilingualTitle == true ? this.langs : [this.lang];
        this.languagesUnit = this.multilingualUnit == true ? this.langs : [this.lang];

    },
    methods: {
        setMultilingual(){
                if (this.languages.length == 1) {
                    this.languages = this.langs;
                }else {
                    this.languages = [this.lang];
                }
                this.setDefaultValues();
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
            axios.post('/back/parameter-update/' + this.item.id,{
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
                                                oldParamValues: this.oldPropValues,
                                                oldPropSuffix : this.oldPropSuffix,
                                                propSuffix: this.propSuffix,
                                                categories: this.checkedCategories,
                                                groupId: this.groupId,
                                            })
                .then(response => {
                    if (response.data == true) {
                        location.reload();
                    }
            })
        },
        setDefaultValues(){
            let vm = this;
            this.parameter.translations.forEach(function(item){
                vm.name[item.lang_id] = item.name;
                vm.unit[item.lang_id] = item.unit;
            });
        },
        getOldParametersValues(){
            let vm = this;
            let defaultVal = [];
            let defaultPrexif = [];

             this.item.parameter_values.forEach(function (entry, key) {
                defaultVal[entry.id] = vm.getOldValue(entry);
                defaultPrexif[entry.id] = entry.suffix;
             })
             this.oldPropValues = defaultVal;
             this.oldPropSuffix = defaultPrexif;
        },
        getOldValue(entry){
            let defaultVal = [];
            entry.translations.forEach(function(translation){
                defaultVal[translation.lang_id] = translation.name;
            });
            return defaultVal;
        },
        addNewRow(){
            let vm = this;
            let ret = [];
            let defalutVal = this.parametersValues;
            let defalutSuf = this.propSuffix;
            this.parametersValues = [];
            this.propSuffix = [];

            vm.languages.forEach(function(lang){
                ret[lang.id] = '';
            });
            defalutVal.push(ret);
            defalutSuf.push([]);
            this.parametersValues = defalutVal;
            this.propSuffix = defalutSuf;
            // this.propSuffix = [];
        },
        removeOldValue(id){
            if (confirm('Do you want to delete this value?')) {
                axios.post('/back/remove-old-value', {id: id, parameterId: this.item.id})
                    .then(response => {
                        this.item = response.data;
                        this.getOldParametersValues();
                        this.setDefaultValues();
                })
            }
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
                        if (child.children.length) {
                            child.children.forEach(function(child2){
                                ret[child2.id] = vm.checkStatus;
                            });
                        }
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
                    if (entry.children.length > 0) {
                        entry.children.forEach(function(child){
                            ret[child.id] = vm.checkedCategories[entry.id];
                            if (child.children.length > 0) {
                                child.children.forEach(function(child2){
                                    ret[child2.id] = vm.checkedCategories[child.id];
                                });
                            }
                        });
                    }
                }else{
                    if (entry.children.length > 0) {
                        entry.children.forEach(function(child){
                            if (id == child.id) {
                                ret[child.id] = vm.checkedCategories[child.id];
                                if (child.children.length > 0) {
                                    child.children.forEach(function(child2){
                                        ret[child2.id] = vm.checkedCategories[child.id];
                                    });
                                }
                            }
                        });
                    }
                }
            });

            this.checkedCategories = ret;
        },
        setCategoriesDefaultValues(){
            let vm = this;
            let ret = [];
            this.categories.forEach(function(entry){
                ret[entry.id] = vm.checkParameterCategory(entry.id);
                if (entry.children.length) {
                    entry.children.forEach(function(child){
                        ret[child.id] = vm.checkParameterCategory(child.id);
                        if (child.children.length) {
                            child.children.forEach(function(child2){
                                ret[child2.id] = vm.checkParameterCategory(child2.id);
                            });
                        }
                    });
                }
            });
            this.checkedCategories = ret;
        },
        checkParameterCategory(categoryId)
        {
            let vm = this;
            let ret = false;
            if (vm.parameter.categories.length) {
                this.parameter.categories.forEach(function(entry){
                    if (entry.category_id === categoryId) {
                        ret = true;
                        return ret;
                    }
                });
            }
            return ret;
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
        width: 14px;
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
