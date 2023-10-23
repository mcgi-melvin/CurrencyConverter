import { defineStore } from 'pinia'
import axios from "axios";
import {reactive, ref} from "vue";

export const app = defineStore('app', () => {
    const
        currency_codes = reactive([]),
        currency_symbols = reactive([])

    function get_server_url()
    {
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1")
            return "http://localhost:3001/server/"
        return "https://currency-converter.melvinlomibao.com/server/"
    }

    function request( action = "", params = {}, method = "get" )
    {
        let args = {}

        for ( let key in params )
            args[key] = params[key]

        args.action = action

        return axios({
            method: method,
            url: get_server_url(),
            params: args
        });
    }

    async function get_data()
    {
        const result = await request('get_data');
        if( result.status === 200 ) {
            for ( let key in result.data )
                app()[key] = result.data[key]
        }
    }

    function get_currency_symbol( currency ) {
        if( currency_symbols.value.some( item => item.abbreviation === currency ) ) {
            const currency = currency_symbols.value.find( item => item.abbreviation === currency )
            return currency.symbol
        }
        return ""
    }

    return { get_data, request, currency_codes, currency_symbols }
})
