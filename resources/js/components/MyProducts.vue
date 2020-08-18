<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProduct">
                    <i class="fa fa-plus"></i> Product
                </button>
                <a href="/category" class="btn btn-info btn-sm" >Categories</a> 
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#newOrder">
                    <i class="fa fa-plus"></i> Quick Order
                </button>
            </h6>
            <!-- Add Product Modal -->
            <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button @click="clear()" class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="addProduct">
                                <div class="form-group">
                                    <label >Product name</label>
                                    <input v-model="product.product_name" name="product_name" type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label >Zip Code</label>
                                    <input v-model="product.bar_code" name="bar_code" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label >SRP</label>
                                    <input v-model="product.srp" name="srp" type="number" step=0.01 class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label >Price</label>
                                    <input v-model="product.product_price" name="product_price" step=0.01 type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Quantity</label>
                                    <input v-model="product.product_qty" value="0" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label >Category</label>
                                    <select v-model="product.category_id" name="category_id" type="number" class="form-control" required>
                                        <option v-bind:key="category.id" v-for="category in categories" :value="category.id">
                                            {{ category.category_name }}
                                        </option>
                                    </select>
                                </div>
                                <button click="hideModal" type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Modal -->
            <div class="modal fade" id="newOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Quick Order</h5>
                            <button  class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form >
                                <div class="form-group">
                                    <label >Products</label>
                                    <select v-model="product.id" name="category_id" type="number" class="form-control" required>
                                        <option v-bind:key="product.id" v-for="product in products" :value="product.id">
                                            {{ product.product_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label >SRP</label>
                                    <input  name="srp" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label >Price</label>
                                    <input name="product_price" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Quantity</label>
                                    <input  value="0" type="number" class="form-control" required>
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
                <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>SRP</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-bind:key="product.id" v-for="product in products">
                            <td>{{ product.product_name }}</td>
                            <td style="font-weight:900">{{ product.bar_code }}</td>
                            <td align="right" style="font-weight:900">{{ product.srp | currency }}</td>
                            <td align="right" style="color:green; font-weight:900">{{ product.product_price | currency }}</td>

                            <td align="center" v-if="product.product_qty == 0" style="color:red; ">Out of Stock</td>
                            <td align="center" v-else-if="product.product_qty < 30" style="color:red; font-weight:900">{{ product.product_qty }}</td>
                            <td align="center" v-else style="color:green;">{{ product.product_qty }}</td>

                            <td >{{ product.category_name }}</td>
                            <td>
                                <button @click="editProduct(product)" data-toggle="modal" data-target="#addProduct" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                <button @click="deleteProduct(product.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table> -->

                <div class="tableFilters">
                    <input class="form-control float-left col-9" type="text" v-model="tableData.search" placeholder="Search"
                        @input="fetchProducts()">
                    <div class="select">
                        <select class="form-control float-right col-1" v-model="tableData.length" @change="fetchProducts()">
                            <option value="10" selected="selected">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>   
                    </div>
                </div>
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy" >
                    <tbody>
                        <tr v-bind:key="product.id" v-for="product in products">
                            <td style="font-weight:400">{{ product.product_name | capitalize }}</td>
                            <td style="font-weight:900">{{ product.bar_code }}</td>
                            <td align="right" style="font-weight:900">{{ product.srp | currency }}</td>
                            <td align="right" style="color:green; font-weight:900">{{ product.product_price | currency }}</td>

                            <td align="center" v-if="product.product_qty == 0" style="color:red; ">Out of Stock</td>
                            <td align="center" v-else-if="product.product_qty < 30" style="color:red; font-weight:900">{{ product.product_qty }}</td>
                            <td align="center" v-else style="color:green;">{{ product.product_qty }}</td>

                            <td >{{ product.category_name }}</td>
                            <td>
                                <button @click="editProduct(product)" data-toggle="modal" data-target="#addProduct" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                <button @click="deleteProduct(product.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>            
                </datatable>
            </div>
        </div>
    </div>
</template>

<script>
import Datatable from './Datatable.vue';
import Pagination from './Pagination';

export default {
    props: ['user'],
    components: { datatable: Datatable, pagination: Pagination },
    data() {
        let sortOrders = {};

        let columns = [
            {label: 'Product Name', name: 'product_name'},
            {label: 'Code', name: 'bar_code'},
            {label: 'SRP', name: 'srp'},
            {label: 'Price', name: 'product_price'},
            {label: 'Qty', name: 'product_qty'},
            {label: 'Category', name: 'category_name'},
            {label: 'Action', name: 'action'},
        ];

        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });
        return {
            products: [],
            mostBoughtProds: [],
            product: {
                id: '',
                user_id: this.user,
                product_name: '',
                bar_code: '',
                srp: 0,
                product_price: '',
                product_qty: 0,
                category_id: '',
            },
            edit: false,
            categories: [],

            // ------------------

            columns: columns,
            sortKey: 'created_at',
            sortOrders: sortOrders,
            tableData: {
                draw: 0,
                length: 10,
                search: '',
                column: 0,
                dir: 'desc',
            },
            pagination: {
                lastPage: '',
                currentPage: '',
                total: '',
                lastPageUrl: '',
                nextPageUrl: '',
                prevPageUrl: '',
                from: '',
                to: '',
            },
        }
    },

    created(){
        this.fetchProducts();
    },

    methods: {
        fetchProducts(url = 'api/get_my_products_joined'){
            this.tableData.draw++;
            axios.get(url, {params: this.tableData})
                .then(response => {
                    let data = response.data;
                    if(this.tableData.draw == data.draw){
                        this.products = data.data.data;
                        this.configPagination(data.data);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                });
        },

        configPagination(data){
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },

        sortBy(key){
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.tableData.column = this.getIndex(this.columns, 'name', key);
            this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
            this.fetchProducts();
        },

        getIndex(array, key, value){
            return array.findIndex(i => i[key] == value);
        }

    }
}
</script>