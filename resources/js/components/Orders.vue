<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <button  v-if="products.length == 0" disabled data-toggle="modal" data-target="#orderProduct" class="btn btn-primary btn-sm" type="submit">
                    Order Product
                </button>
                <button v-else data-toggle="modal" data-target="#orderProduct" class="btn btn-primary btn-sm" type="submit">
                    Order Product
                </button>
                <a href="/order_invoice" class="btn btn-info btn-sm float-right" type="submit">
                    Order Invoice
                </a>
            </h6>

            <div class="modal fade" id="orderProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Enter Quantity</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Products</label>
                                <select v-model="product_id" name="product_id" type="number" class="form-control" required>
                                    <option v-bind:key="product.id" :value="{ id: product.id, text: product.product_name }" v-for="product in products" >
                                        {{ product.product_name | capitalize}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label >SRP</label>
                                <input v-model="product_srp" name="srp" type="number" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Quantity</label>
                                <input v-model="product_qty" name="product_qty" type="number" class="form-control" required>
                            </div>
                            <button @click="pinOrder" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="white-space:nowrap;">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SRP</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order_detail in order_details" v-bind:key="order_detail.id">
                            <td>{{ order_detail.product_name | capitalize }}</td>
                            <td align="right">{{ order_detail.product_srp }}</td>
                            <td align="center">{{ order_detail.product_qty }}</td>
                            <td align="right">{{ (order_detail.product_srp * order_detail.product_qty) | currency }}</td>
                        </tr>
                    </tbody>
                </table>
                <form @submit.prevent="addOrder">
                    <button v-if="order_details == ''" disabled class="btn btn-danger btn-sm float-right">Cancel</button>
                    <button v-else @click="cancel()" class="btn btn-danger btn-sm float-right">Cancel</button>
                  
                    <button v-if="order_details == ''" disabled class="btn btn-primary btn-sm float-right" type="button">Order</button>
                    <button v-else class="btn btn-primary btn-sm float-right" type="submit">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            product: {
                id: '',
            },
            order_details: [],
            product_id: '',
            product_srp: '',
            product_qty: '',
            product_name: '',
            edit: false,
        }
    },

    created(){
        this.fetchProducts();
    },

    methods: {
        fetchProducts(){
            fetch('api/products')
            .then(res => res.json())
            .then(res => {
                this.products = res.data;
            })
        },

        pinOrder(){
            var my_order = {
                product_id: this.product_id.id,
                product_srp: this.product_srp,
                product_qty: this.product_qty,
                product_name: this.product_id.text,
            }
            this.order_details.push(my_order);
            this.product_id = '';
            this.product_srp = '';
            this.product_qty = '';
        },

        addOrder(){
            if(this.edit === false)
            {
                console.log(this.order_details);
                fetch('api/order_invoice', {
                    method: 'post',
                    body: JSON.stringify(this.order_details),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    swal("Done!", "Order Successful", "success");
                    this.order_details = [];
                    this.product_id = '';
                    this.product_srp = '';
                    this.product_qty = '';
                })
            }
        },

        cancel(){
            this.order_details = [];
            this.product_id = '';
            this.product_srp = '';
            this.product_qty = '';
        }
    
    }
}
</script>