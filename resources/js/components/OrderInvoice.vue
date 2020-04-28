<template>
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <div class="modal fade" id="orderDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Invoice No. {{order_invoice_no }}</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="white-space:nowrap;">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-bind:key="detail.id" v-for="detail in order_detail">
                                        <td>{{ detail.product_name | capitalize }}</td>         
                                        <td align="right" style="color:green;">{{ detail.product_srp | currency }}</td>     
                                        <td align="center">{{ detail.product_qty }}</td>     
                                        <td align="right" style="color:green; font-weight: 700;">{{ detail.sub_total | currency }}</td>   
                                    </tr>                     
                                    <tr v-bind:key="detail.id" v-for="detail in order_detail.slice(order_detail.length-1, order_detail.length)">
                                        <td ></td>         
                                        <td></td>     
                                        <td>Grand Total: </td>     
                                        <td align="right">
                                            <span style="font-weight: 700; color:red;">
                                                {{ detail.grand_total | currency }}
                                            </span>
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <div class="tableFilters">
                    <input class="form-control float-left col-9" type="text" v-model="tableData.search" placeholder="Search"
                        @input="fetchOrderInvoice()">
                    <div class="select">
                        <select class="form-control float-right col-1" v-model="tableData.length" @change="fetchOrderInvoice()">
                            <option value="10" selected="selected">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>   
                    </div>
                </div>
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy" > 
                    <tbody>
                        <tr v-for="order_invoice in order_invoices" :key="order_invoice.id">
                            <!-- <td>{{ order_invoice.created_at | moment("dddd, MMMM Do YYYY") }}</td> -->
                            <td>{{ order_invoice.created_at  | moment("dddd, MMMM Do YYYY") }}</td>
                            <td style="font-weight: 700">#{{ order_invoice.id  }}</td>
                            <td align="right" style="color:green; font-weight: 700">{{ order_invoice.grand_total | currency }}</td>
                            <td>
                                <button @click="fetchInvoiceDetails(order_invoice.id)" data-toggle="modal" data-target="#orderDetails" class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td></td> 
                            <td align="right">Grand Total:</td> 
                            <td align="right">
                                <span style="margin-left: -15%; color:red; font-weight: 900; border: 1px solid grey; border-radius: 5px; padding: 5px;">
                                    
                                    {{ sum_grand_total | currency  }}
                                </span>
                            </td> 
                            <td></td> 
                        </tr>
                    </tbody>            
                </datatable>
                <pagination :pagination="pagination" 
                    @prev="fetchOrderInvoice(pagination.prevPageUrl)"
                    @next="fetchOrderInvoice(pagination.nextPageUrl)">
                </pagination>
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
            {width: '53%', label: 'Date', name: 'created_at'},
            {width: '23%', label: 'Invoice No.', name: 'id'},
            {width: '23%', label: 'Total', name: 'grand_total'},
            {width: '33%', label: 'Detail', name: 'detail'},
        ];

        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });
        return {
            order_invoices: [],
            order_detail: [],
            sum_grand_total: 0,
            order_invoice_no: '',

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
        this.fetchGrandTotal();
        this.fetchOrderInvoice();
    },

    methods: {
        fetchOrderInvoice(url = 'api/get_order_invoices'){
            this.tableData.draw++;
            axios.get(url, {params: this.tableData})
                .then(response => {
                    let data = response.data;
                    if(this.tableData.draw == data.draw){
                        this.order_invoices = data.data.data;
                        this.configPagination(data.data);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                });
        },

        fetchGrandTotal(){
            fetch('api/order_invoice_gt')
            .then(res => res.json())
            .then(res => {
                this.sum_grand_total = res.data[0];
            })
        },

        fetchInvoiceDetails(id){
            fetch(`api/order_invoice_details/${id}`, {
                method: 'get'
            })
            .then(res => res.json())
            .then(res => {
                this.order_detail = res.data;
                this.order_invoice_no = id;
            })
        },

        fetchGrandTotal(){
            fetch('api/sales_invoice_gt')
            .then(res => res.json())
            .then(res => {
                this.sum_grand_total = res.data[0];
            })
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
            this.fetchOrderInvoice();
        },

        getIndex(array, key, value){
            return array.findIndex(i => i[key] == value);
        }

    }
}
</script>