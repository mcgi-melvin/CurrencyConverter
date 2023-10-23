<template>
    <div class="currency-filter">
        <label for="currency"></label>
        <input id="currency" placeholder="Search Currency" v-model="keyword">
    </div>
    <div class="currency-list">
        <a href="javascript:void(0)" v-for="(currency) in codes" :class="`currency-item currency-${currency.code.toLowerCase()} ${is_active( currency.code ) ? 'active' : ''}`" @click="set_active_currency( currency.code )">
            <span class="close" v-if="is_active( currency.code )">-</span>
            <span class="symbol" v-html="currency.symbol"></span>
            <div class="text-center">
                <h6>{{ currency.name }}</h6>
                <h3>{{ currency.code }}</h3>
                <p class="currency-value" v-if="conversion_currencies && conversion_currencies.length && convert_store.get_latest_conversion( conversion_currencies ? conversion_currencies[0] : '', currency.code )"><span v-html="currency.symbol"></span> {{ convert_store.get_latest_conversion( conversion_currencies ? conversion_currencies[0] : '', currency.code ) }}</p>
            </div>
        </a>
    </div>
    <Convert v-if="convert_store.show_box" />
</template>

<script>
import {app} from "@/stores/app";
import {convert} from "@/stores/convert";
import {mapActions} from "pinia";
import Convert from "@/views/Convert.vue";

export default {
    components: {Convert},
    data() {
        return {
            convert_store: convert(),
            keyword: null,
            show_convert_box: false
        }
    },
    computed: {
        codes() {
            return app().currency_codes.filter(item => this.keyword ? item.code.toLowerCase().includes(this.keyword.toLowerCase()) || item.name.toLowerCase().includes(this.keyword.toLowerCase()) : true)
        },
        conversion_currencies() {
            return convert().currencies
        },
        conversion_value() {
            return convert().conversion_value
        }
    },
    methods: {
        set_active_currency( currency ) {
            if( !convert().currencies.some(cur => cur.toLowerCase() === currency.toLowerCase()) && convert().currencies.length < 2 ) {
                convert().currencies.push( currency )
            } else {
                this.remove_currency( currency )
            }

            if( convert().currencies && convert().currencies.length > 1 )
                convert().toggle_convert_box()
        },
        is_active( currency ) {
            return convert().currencies.includes(currency);
        },
        remove_currency( currency ) {
            if( convert().currencies.includes( currency ) ) {
                const currency_index = convert().currencies.indexOf(currency)
                if( currency_index > -1 )
                    convert().currencies.splice(currency_index, 1)
            }
        }
    },
    mounted() {
        convert().$subscribe(async (mutation, state) => {
            const currencies = state.currencies
            if( currencies && currencies.length < 2 )
                convert().values = await convert().get_values()
        })
    },
    created() {
        app().get_data()
    }
}
</script>

<style scoped>
.currency-filter {
    margin: 50px 0 0;
}

.currency-filter input {
    color: #fff;
    font-size: 20px;
    width: 100%;
    height: 50px;
    background-color: transparent;
    border-top: none;
    border-left: none;
    border-right: none;
    outline: none;
}

.currency-filter input::placeholder {
    color: #fff;
}

.currency-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    grid-gap: 30px;
    margin: 50px 0 0;
}

.currency-item {
    position: relative;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 200px;
    height: 200px;
    background-color: #141414;
    box-shadow: 0 0 5px rgba(20, 20, 20, 0.5);
    transition: all 0.5s ease-in-out;
}

.currency-item:hover,
.currency-item.active {
    background-color: #242424;
}

.currency-item .close {
    position: absolute;
    top: -10px;
    right: -10px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
}

.currency-item .symbol {
    position: absolute;
    right: 0;
    bottom: 0;
    font-size: 70px;
    font-weight: 700;
    line-height: 1;
    color: dimgrey;
}

.currency-item h3 {
    position: relative;
    font-weight: 700;
    pointer-events: none;
}

.currency-item .currency-value {
    color: gold;
}
</style>