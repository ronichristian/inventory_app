<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button @click="clear()" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategory">Add Category</button>
                <a href="/products" class="btn btn-primary btn-sm float-right">Go back</a>
            </h6>

            <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button  class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="addCategory" >
                                <div class="form-group">
                                    <label>Category name</label>
                                    <input v-model="category.category_name" name="category_name" type="text" class="form-control" required>
                                </div>
                                <button click="hideModal" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-bind:key="category.id" v-for="category in categories">
                            <td>{{ category.category_name }}</td>
                            <td>
                                <button @click="updateCategory(category)" data-toggle="modal" data-target="#addCategory" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                <button @click="deleteCategory(category.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [],
            category:{
                id: '',
                category_name: '',
            },
            edit:false
        }
    },

    created(){
        this.featchCategories();
    },

    methods: {
        featchCategories(){
            fetch('api/categories')
            .then(res => res.json())
            .then(res => {
                this.categories = res.data
            })
        },

        addCategory(){
            if(this.edit === false)
            {
                fetch('api/categories', {
                    method: 'post',
                    body: JSON.stringify(this.category),
                    headers: {
                        'content-type':  'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.category.id = '';
                    this.category.category_name = '';
                    swal("Done!", "Category Added", "success");
                    this.featchCategories();
                })
            }
            else
            {
                fetch('api/categories', {
                    method: 'put',
                    body: JSON.stringify(this.category),
                    headers: {
                        'content-type':  'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.id = '';
                    this.category_name = '';
                    swal("Done!", "Category Updated", "success");
                    this.featchCategories();
                })
            }
        },

        deleteCategory(id){
            swal({
                title: "Are you sure?",
                text: "Confirm to Share List",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        fetch(`/api/categories/${id}`, {
                            method: 'delete'
                        })
                        .then(res => res.json())
                        .then(data => {
                            swal("Done!", "Category Delete", "success");
                            this.featchCategories();
                        })
                    }
                });
        }, 

        updateCategory(category){
            this.edit = true;
            this.category.id = category.id;
            this.category.category_name = category.category_name;
        },

        clear(){
            this.edit = false;
            this.category.id = '';
            this.category.category_name = '';
        }
    }
}
</script>