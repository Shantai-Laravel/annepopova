<template>

    <div class="categories-cover">
        <vue-nestable v-model="categories"
                        :maxDepth="maxDepth"
                        @change="change"
                        :hooks="{
                            'beforeMove': beforeMove,
                          }">
            <vue-nestable-handle slot-scope="{ item }" :item="item">
                <div class="row">
                    <div class="col-md-7">
                        <i class="fa fa-ellipsis-v"></i>
                        <span>{{ item.translation.name }}</span>
                    </div>
                    <div class="col-md-5 text-right">
                        <a :href="'/back/blogs/category/' + item.id" v-if="item.blogs.length || !item.children.length"><i class="fa fa-eye"></i></a>

                        <a href="#" data-toggle="modal" @click="setCurrentCategory(item)" data-target="#addNew" v-if="!item.blogs.length && item.level < maxDepth"><i class="fa fa-plus"></i></a>
                        <a href="#" v-else><i class="fa fa-minus"></i></a>

                        <a :href="'/back/blog-categories/' + item.id + '/edit'"><i class="fa fa-edit"></i></a>
                        <a href="#" @click="remove(item)"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </vue-nestable-handle>
        </vue-nestable>

        <!-- Add new Modal -->
        <div v-if="currentCategory" class="modal fade settings-modal" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">Add new in "<b>{{ currentCategory.translation ? currentCategory.translation.name : ''}}</b>"</h6>
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

        <!-- Remove Allert Modal -->
        <div v-if="currentCategory" class="modal fade settings-modal" id="allertRemove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">Warning removing "<b>{{ currentCategory.translation ? currentCategory.translation.name : ''}}</b>"</h6>
                    </div>
                    <div class="modal-body" v-if="Object.keys(currentCategory).length">
                        <div class="row" v-if="currentCategory.blogs.length > 0">
                            <p class="text-center text-danger">"<b>{{ currentCategory.translation ? currentCategory.translation.name : ''}}</b>" contains blogs, you want to move them to another category?</p>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="categoryToMove">
                                        <option value="0">---</option>
                                        <option :value="category.id" v-for="category in allCategories" v-if="!category.children.length && category.id !== currentCategory.id">
                                            {{ category.translation.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="currentCategory.children.length > 0">
                            <p class="text-center text-danger">"<b>{{ currentCategory.translation ? currentCategory.translation.name : ''}}</b>" contains categories, you want to move them to another category?</p>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" v-model="categoryToMove">
                                        <option value="0">---</option>
                                        <option :value="category.id" v-for="category in allCategories" v-if="!category.blogs.length && category.level < maxDepth && category.id !== currentCategory.id">
                                            {{ category.translation.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="moveRevome">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valiadate Allert Modal -->
        <div v-if="currentCategory" class="modal fade settings-modal" id="allertValiadate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h6 class="modal-title">Warning, "<b>{{ errorParent.translation ? errorParent.translation.name : ''}}</b>" contain blogs</h6>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-center">
                            Do you want to move the blogs to "<b>{{ errorChild.translation ? errorChild.translation.name : ''}}</b>"
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" @click="moveBlogs">Da</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Nu</button>
                        </div>
                    </div><hr>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { bus } from '../../../app';
import { VueNestable, VueNestableHandle } from 'vue-nestable';

export default {
    props: ['langs'],
    data(){
        return {
            categories: [],
            maxDepth: 3,
            titles: [],
            currentCategory: [],
            errorParent: [],
            errorChild: [],
            allCategories: [],
            categoryToMove: 0,
        }
    },
    mounted(){
        this.load();
        this.setTitles();
        bus.$on('refreshCategoriesList', data => {
            this.categories = data.categories;
        });
    },
    methods: {
        load(){
            axios.post('/back/blog-categories/get-categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(e => { console.log('error load ctegories') });
        },
        setTitles(){
            let ret = [];
            this.langs.forEach(function(entry){
                ret[entry.id] = '';
            })
            this.titles = ret;
        },
        change(){
            let validate = this.validate();
            if (!validate.staus) {
                return this.valiadateError(validate.parent, validate.child);
            }
            axios.post('/back/blog-categories/change-position', {categories: this.categories})
                .then(response => {
                    this.categories = response.data.categories;
                })
                .catch(e => { console.log('error load ctegories') });
        },
        validate(){
            let vm = this;
            let ret = {staus : true};

            // level 1
            this.categories.forEach(function(cat1){
                if (cat1.children.length > 0) {
                    cat1.children.forEach(function(cat2){
                        if (cat1.blogs.length > 0) return ret = {status: false, parent: cat1, child: cat2};
                        // level 2
                        if (cat2.children.length > 0) {
                            cat2.children.forEach(function(cat3) {
                                if (cat2.blogs.length > 0) return ret = {status : false, parent: cat2, child: cat3};
                                // level 3
                                if (cat3.children.length > 0) {
                                    cat3.children.forEach(function(cat4) {
                                        if (cat3.blogs.length > 0) return ret = {status : false, parent: cat3, child: cat4};
                                        // level 4
                                        if (cat4.children.length > 0) {
                                            cat4.children.forEach(function(cat5) {

                                                if (cat4.blogs.length > 0) return ret = {status : false, parent: cat4, child: cat5};

                                            })
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            })

            return ret;
        },
        valiadateError(parent, child){
            $('#allertValiadate').modal('show');
            this.errorParent = parent;
            this.errorChild = child;
            this.load();
        },
        moveBlogs(){
            axios.post('/back/blog-categories/move-blogs', {parent_category:this.errorParent.id, child_category: this.errorChild.id})
                .then(response => {
                    this.categories = response.data;
                })
                .catch(e => { console.log('error remove categories') });
        },
        beforeMove({ dragItem, pathFrom, pathTo }) {

        },
        remove(category){
            if (!category.blogs.length) {
                if (!category.children.length) {
                    if (confirm("Do you really want to remove this category?")) {
                        axios.post('/back/blog-categories/remove', {category_id: category.id})
                            .then(response => {
                                this.categories = response.data;
                            })
                            .catch(e => { console.log('error remove categories') });
                    }
                    return true;
                }
            }

            $('#allertRemove').modal('show');

            axios.post('/back/blog-categories/get-all-categories', {category_id: category.id})
                .then(response => {
                    this.allCategories = response.data;
                    this.currentCategory = category;
                })
                .catch(e => { console.log('error load all categories') });
        },
        moveRevome(){
            axios.post('/back/blog-categories/remove-moving-category', {category_to_move: this.categoryToMove, category_id: this.currentCategory.id})
                .then(response => {
                    this.categories = response.data;
                    this.categoryToMove = 0;
                })
                .catch(e => { console.log('error remove categories') });
        },
        setCurrentCategory(category){
            this.currentCategory = category;
            this.setTitles();
        },
        addSave(){
            axios.post('/back/blog-categories/add-new', {titles: this.titles, categoryId: this.currentCategory.id, level: this.currentCategory.level})
                .then(response => {
                    bus.$emit('refreshCategoriesList', {categories: response.data.categories});
                })
                .catch(e => { console.log('error add new category') });
        },
        addEdit(){
            axios.post('/back/blog-categories/add-new', {titles: this.titles, categoryId: this.currentCategory.id, level: this.currentCategory.level})
                .then(response => {
                    window.location.href = window.location.origin + '/back/blog-categories/'+ response.data.category.id +'/edit';
                    bus.$emit('refreshCategoriesList', {categories: response.data.categories});
                })
                .catch(e => { console.log('error add new category') });
        },
    },
    components: {
        VueNestable,
        VueNestableHandle
    },
}
</script>
