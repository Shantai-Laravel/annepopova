<template>

    <div>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addNewModal"><i class="fa fa-plus"></i> Add new category</a>
        <!-- Modal -->
        <div class="modal fade settings-modal" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add new Category</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group" v-for="lang in langs">
                              <div class="row">
                                  <div class="col-md-2 text-right">
                                      <label for="">Title [{{ lang.lang }}]</label>
                                  </div>
                                  <div class="col-md-10">
                                      <input type="text" class="form-control" v-model="titles[lang.id]">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-12">
                      <div class="col-md-6">
                          <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" aria-label="Close" @click="addSave">Add & Close</button>
                      </div>
                      <div class="col-md-6">
                          <button type="button" class="btn btn-primary btn-block" @click="addEdit">Add & Edit</button>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>

</template>

<script>

import { bus } from '../../../app';

export default {
    props: ['langs'],
    data(){
        return {
            titles: [],
            addedCategory: [],
        }
    },
    mounted(){
        this.setTitles();
    },
    methods: {
        setTitles(){
            let ret = [];
            this.langs.forEach(function(entry){
                ret[entry.id] = '';
            })
            this.titles = ret;
        },
        addSave(){
            axios.post('/back/blog-categories/add-new', {titles: this.titles})
                .then(response => {
                    bus.$emit('refreshCategoriesList', {categories: response.data.categories});
                    this.setTitles();
                })
                .catch(e => {
                    console.log('error add new category');
                });
        },
        addEdit(){
            axios.post('/back/blog-categories/add-new', {titles: this.titles})
                .then(response => {
                    window.location.href = window.location.origin + '/back/blog-categories/'+ response.data.category.id +'/edit';
                    bus.$emit('refreshCategoriesList', {categories: response.data.categories});
                    this.setTitles();
                })
                .catch(e => {
                    console.log('error add new category');
                });
        },
        save(){

        }
    }
}
</script>
