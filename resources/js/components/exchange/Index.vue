<template>
    <div class="container">
        <div class="row justify-content-center exchange-box">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Amount"
                                       v-model="amount"
                                       pattern="^[0-9\.]+$"
                                       autofocus>
                            </div>
                            <div class="col-sm-4 px-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">From</span>
                                    </div>
                                    <select class="form-control" v-model="sourceCurrencySymbol">
                                        <option v-for="currency in currencies"
                                                :selected="currency.symbol === sourceCurrencySymbol"
                                                :value="currency.symbol"
                                        >{{currency.symbol}} - {{currency.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">To</span>
                                    </div>
                                    <select class="form-control" v-model="targetCurrencySymbol">
                                        <option v-for="currency in currencies"
                                                :selected="currency.symbol === targetCurrencySymbol"
                                                :value="currency.symbol"
                                        >{{currency.symbol}} - {{currency.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <p class="text-danger" v-if="errors" v-for="error in errors">{{error[0]}}<br /></p>
                                <strong v-if="loading">Loading...</strong>
                                <strong class="result" v-show="result && amount">
                                    {{parseFloat(amount)}} <span class="text-success">{{sourceCurrencySymbol}}</span> = {{parseFloat(parseFloat(result).toFixed(3))}} <span class="text-danger">{{targetCurrencySymbol}}</span>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['inputCurrencies'],

        data() {
            return {
                currencies: [],
                sourceCurrencySymbol: '',
                targetCurrencySymbol: '',
                amount: '',
                result: '',

                loading: false,
                errors: []
            }
        },

        mounted() {
            if (this.inputCurrencies) {
                this.currencies = JSON.parse(this.inputCurrencies);

                // Set source and target currencies to first one
                if (this.currencies[0] && this.currencies[1]) {
                    this.sourceCurrencySymbol = this.currencies[0].symbol;
                    this.targetCurrencySymbol = this.currencies[1].symbol;
                }
            }
        },

        methods: {
            convert() {
                // If amount was empty, do not send for conversion
                if (this.amount.length === 0) {
                    return;
                }

                //reset result
                this.result = '';

                this.loading = true;

                axios.get('/ajax/currencies/convert', {
                    params: {
                        source: this.sourceCurrencySymbol,
                        target: this.targetCurrencySymbol,
                        amount: this.amount
                    }
                }).then(response => {
                    this.result = response.data.result;
                    this.errors = [];
                    this.loading = false;
                }).catch((error) => {
                    this.errors = Object.values(error.response.data.errors);
                    this.loading = false;
                });
            },
        },

        watch: {
            targetCurrencySymbol: function(val) {
                if (val === this.sourceCurrencySymbol)
                {
                    this.sourceCurrencySymbol = this.currencies[0].symbol;
                }
                this.convert();
            },

            sourceCurrencySymbol: function(val) {
                if (val === this.targetCurrencySymbol)
                {
                    this.targetCurrencySymbol = this.currencies[0].symbol;
                }
                this.convert();
            },
            amount: function (val) {
                this.convert();
            }
        }
    }
</script>

<style scoped>
    .exchange-box {
        margin-top: 200px;
    }

    .result {
        font-size: 20px;
    }
</style>