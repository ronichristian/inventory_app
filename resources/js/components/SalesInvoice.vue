<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="modal fade" id="invoiceDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Invoice No. {{invoide_id}}</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table style="white-space:nowrap;" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-bind:key="invoice.id" v-for="invoice in invoice_detail">
                                        <td>{{ invoice.product_name | capitalize }}</td>         
                                        <td align="right" style="color:green;">{{ invoice.product_price | currency }}</td>     
                                        <td align="center">{{ invoice.product_qty }}</td>     
                                        <td align="right" style="font-weight: 700;">{{ invoice.sub_total | currency }}</td>                          
                                    </tr>                    
                                    <tr v-bind:key="invoice.id" v-for="invoice in invoice_detail.slice(invoice_detail.length-1, invoice_detail.length)">
                                        <td ></td>         
                                        <td></td>     
                                        <td>Grand Total: </td>     
                                        <td align="right">
                                            <span style="font-weight: 900; border: 1px solid grey; border-radius: 5px; padding: 5px; color:red;">
                                                {{ invoice.grand_total | currency }}
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
                <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="sales_invoice in sales_invoices" v-bind:key="sales_invoice.id">
                            <td align="left">{{ sales_invoice.id }}</td>
                            <td align="left">{{ sales_invoice.created_at }}</td>
                            <td align="right">{{ sales_invoice.grand_total | currency }}</td>
                            <td>
                                <button @click="fetchInvoiceDetails(sales_invoice.id)" data-toggle="modal" data-target="#invoiceDetails" class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td></td> 
                            <td></td> 
                            <td align="right">Grand Total: 
                                <span style="color:green; font-weight: 900; border: 1px solid grey; border-radius: 5px; padding: 5px;">
                                    {{ sum_grand_total | currency }}
                                </span>
                            </td> 
                            <td></td> 
                        </tr>
                    </tbody> 
                </table> -->
                <div class="tableFilters">
                    <input class="form-control float-left col-9" type="text" v-model="tableData.search" placeholder="Search"
                        @input="fetchSalesInvoices()">
                    <div class="select">
                        <select class="form-control float-right col-1" v-model="tableData.length" @change="fetchSalesInvoices()">
                            <option value="10" selected="selected">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>   
                    </div>
                </div>
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy" >
                    <tbody>
                        <tr v-for="sales_invoice in sales_invoices" :key="sales_invoice.id">
                            <td>{{ sales_invoice.created_at | moment("dddd, MMMM Do YYYY") }}</td>
                            <td style="font-weight: 700">#{{ sales_invoice.id  }}</td>
                            <td align="right" style="color:green; font-weight: 700">{{ sales_invoice.grand_total | currency }}</td>
                            <td>
                                <button @click="fetchInvoiceDetails(sales_invoice.id)" data-toggle="modal" data-target="#invoiceDetails" class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td></td> 
                            <td>Grand Total:</td> 
                            <td align="right">
                                <span style="color:red; font-weight: 900; border: 1px solid grey; border-radius: 5px; padding: 5px;">
                                    {{ sum_grand_total | currency }}
                                </span>
                            </td> 
                            <td></td> 
                        </tr>
                    </tbody>            
                </datatable>
                <pagination :pagination="pagination" 
                    @prev="fetchSalesInvoices(pagination.prevPageUrl)"
                    @next="fetchSalesInvoices(pagination.nextPageUrl)">
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
    data(){
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
        return{
            sales_invoices: [],
            sales_invoice: {
                id: '',
                product_id: '',
                product_price: '',
                product_qty: '',
                sub_total: '',
            },
            invoice_detail: [],
            invoide_id: '',
            sum_grand_total: '',
            
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

    mounted(){
        this.fetchSalesInvoices(),
        this.fetchGrandTotal()
    },

    methods: {
        fetchSalesInvoices(url = 'api/get_sales_invoices'){
            this.tableData.draw++;
            axios.get(url, {params: this.tableData})
                .then(response => {
                    let data = response.data;
                    if(this.tableData.draw == data.draw){
                        this.sales_invoices = data.data.data;
                        this.configPagination(data.data);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                });
        },

        fetchInvoiceDetails(id){
            fetch(`api/sales_invoice_details/${id}`, {
                method: 'get'
            })
            .then(res => res.json())
            .then(res => {
                this.invoide_id = id;
                this.invoice_detail = res.data;
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
            this.fetchSalesInvoices();
        },

        getIndex(array, key, value){
            return array.findIndex(i => i[key] == value);
        }

    }
}
</script>