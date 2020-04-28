<template>
    <span>
        {{ this.net_worth | currency }}
    </span>
</template>

<script>


export default {
    data(){
        return {
            net_worth: 0,
        }
    },
    mounted () {
        this.fetchMostBoughtProduct();
        this.net_worth_total();
    },
    methods: {
        fetchMostBoughtProduct(){
            fetch('api/most_bought_products')
            .then(res => res.json())
            .then(res => {
                this.mostBoughtProds = res.data
            })
            .catch(err => console.log(err))
        },

        net_worth_total(){
                fetch('api/net_worth_total')
            .then(res => res.json())
            .then(res => {
                this.net_worth = res.data
            })
        },
    }
}
</script>
