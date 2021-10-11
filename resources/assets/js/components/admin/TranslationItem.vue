<template>

    <div class="col-md-9" v-if="load == true">
        <div class="row">
            <div class="col-md-3">
                <h6>{{ group.key }} Group <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" :data-target="['#addNewItem' + group.id]"> <i class="fa fa-plus"></i> Add new</button></h6>
            </div>
            <div class="col-md-4 form-inline">
                <input type="text" v-model="search" placeholder="Search..." class="input-top-bar">
                <button type="submit" class="btn btn-primary btn-sm" @click="searchTranslation"><i class="fa fa-search"></i></button>
                <button type="submit" class="btn btn-primary btn-sm" @click="cancelSearch"><i class="fa fa-close"></i></button>
            </div>
            <div class="col-md-5">
                <div class="text-danger" v-if="this.searchStatus">
                    <small>{{ items.length }} translations were found!</small>
                </div>
                <div class="text-danger" v-if="searchInfo" v-html="searchInfo"></div>
                <div class="text-danger" v-if="this.validateError">
                    <small>{{ this.validateError }}</small>
                </div>
            </div>
        </div>
        <hr>

        <table class="table border-simple">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="key-column">Key</th>
                    <th v-for="lang in langs">{{ lang.lang }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key) in items">
                    <td class="column">
                        <span class="tool" :data-tip=" item.comment " tabindex="1"></span>
                        {{ key + 1 }}
                    </td>
                    <td class="key-column">
                        <input type="text" @keyup="changeStatus" @keypress="validate(keys[item.id])" v-model="keys[item.id]">
                    </td>
                    <td v-for="lang in langs">
                        <textarea @keyup="changeStatus" v-model="lines[item.id][lang.id]"></textarea>
                    </td>
                    <td>
                        <a href="#" @click.prevent="remove(item.id)"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="modal fade bd-example-modal-lg settings-modal" :id="['addNewItem' + group.id]" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center"><small>Create New Item</small> {{ group.key }} group </h5>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-1 text-right">
                                        <label for="">Key</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="text-danger" v-if="this.validateError">
                                            <small>{{ this.validateError }}</small>
                                        </div>
                                        <input type="text" v-model="key" class="form-control" @keyup="validate(key)">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-1 text-right">
                                        <label for=""><small>Comment</small></label>
                                    </div>
                                    <div class="col-md-10">
                                        <!-- <input type="text" v-model="comment" class="form-control"> -->
                                        <textarea v-model="comment" class="form-control-upgrade"></textarea>
                                    </div>
                                </div><hr>
                            </div>
                            <div class="form-group" v-for="lang in langs">
                                <div class="row">
                                    <div class="col-md-1 text-right">
                                        <label for="">{{ lang.lang }}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <!-- <input type="text" v-model="transNew[lang.id]" class="form-control"> -->
                                        <textarea v-model="transNew[lang.id]" class="form-control-upgrade"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="button" data-dismiss="modal" aria-label="Close" value="Save" class="btn btn-primary btn-block" @click="saveNewLine">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from '../../app';

export default {
    props: ['langs', 'group'],
    data(){
        return {
            load: false,
            items: this.group.translations,
            key: '',
            keys: [],
            comment: '',
            lines: [],
            transNew: [],
            languages: this.langs,
            validateError: false,
            keysList: [],
            search: '',
            searchInfo: '',
            searchStatus: false,
        }
    },
    created(){
        this.getNewLines();
        this.getLines();

        bus.$on('saveGroupInfo' + this.group.id, data => {
            this.update();
        });
        this.load = true;
    },
    methods: {
        getNewLines(){
            let ret = [];
            this.languages.forEach(function(entry){
                 ret[entry.id] = '';
            });

            this.transNew = ret;
        },
        getLines(){
            let vm = this;
            let ret = [];
            let keys = [];
            this.items.forEach(function(entry){
                ret[entry.id] = vm.getLineTranslations(entry);
                keys[entry.id] = entry.key;
            });

            this.keys = keys;
            this.lines = ret;
        },
        getLineTranslations(entry){
            let ret = [];
            entry.lines.forEach(function(line){
                ret[line.lang_id] = line.line;
            });
            return ret;
        },
        saveNewLine(){
            bus.$emit('startLoading');
            this.items = [];
            axios.post('/back/translations-save-new-line', {lines: this.transNew, key : this.key, comment : this.comment, groupId: this.group.id})
                .then(response => {
                    this.items = response.data;
                    this.getLines();
                    this.getNewLines();
                    this.key = '';
                    this.comment = '';
                    bus.$emit('endLoading');
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        changeStatus(){
            bus.$emit('changeSaveStatus', this.group.id);
        },
        update(){
            bus.$emit('startLoading');
            axios.post('/back/translations-update', {lines: this.lines, keys: this.keys, groupId: this.group.id})
                .then(response => {
                    bus.$emit('endLoading');
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        remove(id){
            if (confirm("Do you really want to delete this translation?")) {
                bus.$emit('startLoading');
                this.items = [];
                axios.post('/back/translations-remove', {id: id, groupId: this.group.id})
                    .then(response => {
                        this.items = response.data;
                        this.getLines();
                        bus.$emit('endLoading');
                    })
                    .catch(e => {
                        console.log('error load products');
                    })
            }
        },
        getKeysList(){
            let ret = [];
            this.items.forEach(function(element, index){
                ret[index] = element.key;
            });
            this.keysList = ret;
        },
        validate(key){
            this.getKeysList();
            if (this.keysList.includes(key)) {
                this.validateError = key + ' is already used in this group! Choose another please.';
            }else{
                this.validateError = false;
            }
        },
        searchTranslation(){
            bus.$emit('startLoading');
            this.items = [];
            axios.post('/back/translations-search', {search: this.search,  groupId: this.group.id})
                .then(response => {
                    if (response.data.status == 'true') {
                        this.items = response.data.trans;
                        this.getLines();
                        this.searchStatus = true;
                        this.searchInfo = "";
                        if (response.data.groups.length > 0) {
                            this.searchInfo =  '<i>"' + this.search + '"</i> was found in - <b>"' + response.data.groups.toString() + '"</b> group';
                        }
                    }else{
                        this.searchStatus = true;
                        this.searchInfo += 'try searching <i>"' + this.search + '"</i> in the - <b>"' + response.data.groups.toString() + '"</b> group';
                    }

                    bus.$emit('endLoading');
                })
                .catch(e => {
                    console.log('error load products');
                })
        },
        cancelSearch(){
            bus.$emit('startLoading');
            this.items = [];
            axios.post('/back/translations-cancel-search', {groupId: this.group.id})
                .then(response => {
                    this.items = response.data;
                    this.getLines();
                    this.search = '';
                    this.searchInfo = '',
                    this.searchStatus = false,
                    bus.$emit('endLoading');
                })
                .catch(e => {
                    console.log('error load products');
                })
        }
    }
}
</script>
