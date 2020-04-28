<template>
    <div class="small">
        <line-chart :chart-data="datacollection" :height="200"></line-chart>
    </div>
</template>

<script>
import LineChart from './LineChart.js'

export default {
    components: {
        LineChart
    },
    data () {
        return {
            datacollection: null,
        }
    },
    mounted () {
        this.fillData();
        this.fetchData();
    },
    methods: {
        fetchData(){
            // fetch('api/most_bought_products')
            // .then(res => res.json())
            // .then(res => {
            //     this.products = res.data;
            //     console.log(this.products);
            // })
        },

        fillData (){
            fetch('api/most_bought_products')
            .then(res => res.json())
            .then(res => {
                this.products = res.data;

                this.datacollection = {
                    labels: [],
                    datasets: [  
                        {
                            label: 'Products',
                            backgroundColor: '#90EE90',
                            borderColor: '#ff006673',
                            data: [],
                        },
                        // {
                        //     label: 'Capital',
                        //     backgroundColor: '#ff006673',
                        //     data: [this.products[1]],
                        // },
                    ]
                }
                for(var i=0; i < this.products.length; i++)
                {
                    var names = this.products[i].product_name;
                    var count = this.products[i].total;
                    this.datacollection.labels.push(names);
                    this.datacollection.datasets[0].data.push(count);
                }
                // console.log(this.datacollection.datasets[0].data);

            })
        },

    }
}
</script>

<style>
    .small {
        max-width: 600px;
        margin-top:  3%;
    }
</style>