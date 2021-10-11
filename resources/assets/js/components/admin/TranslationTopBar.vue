<template>

<div class="">
    <div class="loading" v-if="loading"><div class="lds-ripple"><div></div><div></div></div></div>

    <div class="top-bar flex  header-fixed">
        <div class="loading" v-if="loading"><div class="lds-ripple"><div></div><div></div></div></div>
        <div class="col-md-3">
            <button type="button" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#addNew"> <i class="fa fa-plus"></i> Add new group</button>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-4">
            <button type="button" @click="saveAll" :class="['btn', 'btn-sm', 'btn-block', saveBtn]"> <i class="fa fa-save"></i> Save all</button>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <a href="/back" class="btn btn-sm btn-primary btn-block"> <i class="fa fa-backward"></i> Cancel & Back</a>
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg settings-modal" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center">Create New Group</h5>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Key Group (unique)</label>
                            <div class="text-danger" v-if="this.validateError">
                                <small>{{ this.validateError }}</small>
                            </div>
                            <input type="text" v-model="group" @keyup="validate" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Comment</label>
                            <input type="text" v-model="comment" class="form-control">
                        </div>
                        <div class="form-group" v-if="!this.validateError">
                            <input type="button" data-dismiss="modal" aria-label="Close" value="Save" class="btn btn-primary btn-block" @click="createGroup">
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
        // props: ['categories', 'current'],
        data(){
            return {
                ready: false,
                search : '',
                groups: [],
                group: '',
                comment: '',
                saveBtn: 'btn-primary',
                editedGroups: [],
                loading: false,
                keysList: [],
                validateError: false,
            }
        },
        mounted(){
            this.load();

            bus.$on('changeSaveStatus', data => {
                this.saveBtn = 'btn-warning';
                if (!this.editedGroups.includes(data)) {
                    this.editedGroups.push(data);
                }
            })

            bus.$on('sendTranslations', data => {
                this.getKeysList();
            })

            bus.$on('startLoading', data => {
                this.loading = true;
            })

            bus.$on('endLoading', data => {
                let vm = this
                setTimeout(function() {
                    vm.loading = false;
                }, 0);
            })
        },
        methods: {
            load(){
                axios.post('/back/translations-get-groups')
                    .then(response => {
                        this.groups = response.data;
                        bus.$emit('sendTranslations', response.data);
                    })
                    .catch(e => {
                        console.log('loading translations error.');
                    })
            },
            createGroup(){
                axios.post('/back/translations-create-group', {'group' : this.group, 'comment' : this.comment})
                    .then(response => {
                        bus.$emit('sendTranslations', response.data);
                        this.group = "";
                        this.comment = "";
                        $('#addNew').modal('hide');
                    })
                    .catch(e => {
                        console.log('creating group error.');
                    })
            },
            saveAll(){
                if (this.editedGroups.length > 0) {
                    this.editedGroups.forEach(function(entry){
                        bus.$emit('saveGroupInfo' + entry);
                    })
                    this.saveBtn = 'btn-primary';
                    this.editedGroups = [];
                }
            },
            getKeysList(){
                let ret = [];
                this.groups.forEach(function(element, index){
                    ret[index] = element.key;
                });
                this.keysList = ret;
            },
            validate(){
                if (this.keysList.includes(this.group)) {
                    this.validateError =  this.group + ' is already used! Choose another please.';
                }else{
                    this.validateError = false;
                }
            }
        }
    }

</script>

<style media="screen">
    .card-block{
        min-height: 100vh;
    }
</style>
