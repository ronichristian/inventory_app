<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                    
            </h6>
            <!-- Pinned Product Modal -->
            <div class="modal fade" id="pinProduct" role="dialog"  aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Enter Quantity</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label style="font-size: 20px; color:green; margin-left: 35%;">{{ product.product_name | capitalize }}</label>
                            <input v-model="input_qty" style="padding-left: 15%; font-size: 25px; margin-left: 35%; width:150px; height:100px;" class="form-control" name="qty" type="number" step=0.01>
                            <button v-if="input_qty <= 0 " disabled type="button" class="btn btn-primary">
                                Enter Qty
                            </button>
                            <button v-else-if="input_qty > product.product_qty" disabled type="button" class="btn btn-primary">
                                Not enough Qty
                            </button>
                            <button v-else @click="addRowToTable" type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary">
                                Enter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Payment Modal -->
            <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Enter Amount</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="confirmTransaction">
                                <label style="font-size: 20px; color:green; margin-left: 35%;">
                                    Total: {{ grand_total | currency }}
                                </label>
                                <input v-model="amount_entered" style="padding-left: 10%; font-size: 25px; margin-left: 35%; width:150px; height:100px;" class="form-control" name="qty" type="number" step=0.01>
                                <hr>
                                <button v-if="amount_entered < grand_total" disabled type="submit" class="btn btn-primary">Submit</button>
                                <button v-else type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <h5>Pinned Products</h5>
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>QTY</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-bind:key="product.id" v-for="(product, index) in pin_products" >
                            <td>{{ product.product_name | capitalize }}</td>
                            <td align="right" style="color:green; font-weight:700">{{ product.product_price | currency }}</td>
                            <td align="center">{{ product.product_qty }}</td>
                            <td align="right" style="color:red; font-weight:700">{{ (product.product_price * product.product_qty) | currency }}</td>
                            <td>
                                <button @click="removeRow(index)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button v-if="pin_products == ''" disabled class="btn btn-primary btn-sm float-right" type="submit">Confirm Transaction</button>
                <button v-else style="color:red;" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#payment" type="submit">Confirm Transaction</button>
            </div>
            <hr>
            <div class="table-responsive">
                <h5>
                    Products
                    <!-- <input type="text" v-model="search" placeholder="Search" class="form-control float-right col-2" /> -->
                </h5>
                <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-bind:key="product.id" v-for="product in products">
                            <td>{{ product.product_name }}</td>
                            <td align="right">{{ product.product_price | currency }}</td>

                            <td align="center" v-if="product.product_qty == 0" style="color:red; ">Out of Stock</td>
                            <td align="center" v-else-if="product.product_qty < 30" style="color:red; font-weight:900">{{ product.product_qty }}</td>
                            <td align="center" v-else style="color:green;">{{ product.product_qty }}</td>

                            <td v-if="product.product_qty === 0">
                                <button  disabled class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </td>   
                            <td v-else>
                                <button @click="addRow(product)" data-toggle="modal" data-target="#pinProduct" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table> -->
                <div class="tableFilters">
                    <input class="form-control float-left col-9" type="text" v-model="tableData.search" placeholder="Search"
                        @input="getProducts()">
                    <div class="select">
                        <select class="form-control float-right col-1" v-model="tableData.length" @change="getProducts()">
                            <option value="10" selected="selected">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>   
                    </div>
                </div>
                <div>
                    <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy" >
                        <tbody>
                            <tr v-for="project in search_products" :key="project.id">
                                <td>{{ project.product_name | capitalize }}</td>
                                <td align="right" style="color:green; font-weight:700">{{ project.product_price | currency }}</td>

                                <td align="center" v-if="project.product_qty == 0" style="color:red; ">Out of Stock</td>
                                <td align="center" v-else-if="project.product_qty < 30" style="color:red; font-weight:900">{{ project.product_qty }}</td>
                                <td align="center" v-else style="color:green;">{{ project.product_qty }}</td>

                                <td v-if="project.product_qty === 0">
                                    <button  disabled class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i></button>
                                </td>   
                                <td v-else>
                                    <button @click="addRow(project)" data-toggle="modal" data-target="#pinProduct" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i></button>
                                </td>
                            </tr>
                        </tbody>            
                    </datatable>
                    <pagination :pagination="pagination" 
                        @prev="getProducts(pagination.prevPageUrl)"
                        @next="getProducts(pagination.nextPageUrl)">
                    </pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datatable from './Datatable.vue';
import Pagination from './Pagination';

export default {
    components: { datatable: Datatable, pagination: Pagination },
    data() {
        let sortOrders = {};

        let columns = [
            {width: '60%', label: 'Product Name', name: 'product_name'},
            {width: '23%', label: 'Price', name: 'product_price'},
            {width: '40%', label: 'Qty', name: 'product_qty'},
            {width: '40%', label: 'Action', name: 'action'},
        ];

        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });
        return {
            search_products: [],
            columns: columns,
            sortKey: 'product_name',
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
            // ------------------
            products: [],
            product: {
                id: '',
                product_name: '',
                product_price: '',
                product_qty: '',
                category_id: '',
            },
            edit: false,
            pin_products: [],
            input_qty: [],
            grand_total: 0,
            amount_entered: 0,
        }
    },

    created(){
        this.fetchProducts();
        this.getProducts();
    },

    methods: {
        fetchProducts(){
            fetch('api/products')
            .then(res => res.json())
            .then(res => {
                this.products = res.data
            })
            .catch(err => console.log(err))
        },
        calculate_sum(){
            let sum = 0;
            this.pin_products.forEach(element => {
                sum += (parseFloat(element.product_price) * parseFloat(element.product_qty));
            });
            this.grand_total = sum;
        },
        addRow(product){
            this.product.id = product.id;
            this.product.product_name = product.product_name;
            this.product.product_price = product.product_price;
            this.product.product_qty = product.product_qty;
            this.product.category_id = product.category_id;
        },
        addRowToTable(){
            this.product.product_qty = this.input_qty;
            if(this.pin_products.push(this.product))
            {
                this.product = {};
                this.calculate_sum();
            }
            this.input_qty = '';
            this.tableData.search = '';
            this.getProducts();
            $(".modal fade").modal('hide');
        },
        removeRow(index){
            this.pin_products.splice(index, 1);
            this.calculate_sum();
        },
        confirmTransaction(){
            var change = this.amount_entered - this.grand_total;
            swal({
                title: "Are you sure?",
                text: "Confirm Transaction",
                icon: "warning",
                buttons: true,
                })
                .then((willConfirm) => {
                    if (willConfirm) {
                        fetch('api/sales_invoice', {
                            method: 'post',
                            body: JSON.stringify(this.pin_products),
                            headers: {
                                'content-type': 'application/json'
                            }
                        })
                        .then(res => res.json)
                        .then(data => {
                            this.fetchProducts();
                            this.pin_products = [];
                            this.input_qty = [],
                            this.grand_total = 0,
                            this.amount_entered = 0,
                            swal("Your change is " + change.toString(), "", "success");
                        })
                    }
                });
        },

        getProducts(url = 'api/get_products'){
            this.tableData.draw++;
            axios.get(url, {params: this.tableData})
                .then(response => {
                    let data = response.data;
                    if(this.tableData.draw == data.draw){
                        this.search_products = data.data.data;
                        this.configPagination(data.data);
                        $('html,body').animate({
                            scrollTop: $(".tableFilters").offset().top},
                        'slow');
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
            this.getProducts();
        },

        getIndex(array, key, value){
            return array.findIndex(i => i[key] == value);
        }
    }
}
</script>