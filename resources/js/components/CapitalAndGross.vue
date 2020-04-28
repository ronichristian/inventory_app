<template>
    <div class="small">
        <bar-chart :chart-data="datacollection" :height="200"></bar-chart>
    </div>
</template>

<script>
import BarChart from './BarChart.js'

export default {
    components: {
        BarChart
    },
    data () {
        return {
            datacollection: null
        }
    },
    mounted () {
        this.fillData();
    },
    methods: {
        fillData (){
            fetch('api/grand_totals')
            .then(res => res.json())
            .then(res => {
                this.orderInvoiceGrandTotal = res.data;
                console.log(this.orderInvoiceGrandTotal);
                this.datacollection = {
                    labels: ['Gross and Capital'],
                    datasets: [  
                        {
                            label: 'Gross',
                            backgroundColor: '#90EE90',
                            borderColor: '#ff006673',
                            data: [this.orderInvoiceGrandTotal[0]],
                            barPercentage: 0.5,
                            barThickness: 200,
                        },
                        {
                            label: 'Capital',
                            backgroundColor: '#ff006673',
                            data: [this.orderInvoiceGrandTotal[1]],
                            barPercentage: 0.5,
                            barThickness: 200,
                        },
                    ]
                }
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