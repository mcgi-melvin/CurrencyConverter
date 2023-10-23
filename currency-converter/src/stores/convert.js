import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from "axios";
import {app} from "@/stores/app";

export const convert = defineStore('convert', () => {
    let
        currencies = ref([]),
        values = ref([]),
        conversion_value = ref(0),
        show_box = false

    async function get_values() {
        const result = await app().request("convert_currency", { base: currencies.value && currencies.value.length ? currencies.value[0] : '' })
        if( result.status === 200 )
            return result.data
    }

    function get_latest_conversion( base, converting ) {
        const data = values.value.find(item => item.base_currency === base && item.converting_currency === converting)
        if (data)
            return data.value
    }

    function get_conversion_value( base, converting ) {
        const quantity = parseFloat( event.target.value )
        const data = values.value.find(item => item.base_currency === base && item.converting_currency === converting)
        if (data)
            convert().conversion_value = parseFloat( data.value ) * quantity
    }

    function get_conversion_data( base, converting ) {
        const data = values.value.filter(item => item.base_currency === base && item.converting_currency === converting)
        if( data )
            return data
        return []
    }

    function toggle_convert_box(){
        convert().show_box = !show_box
    }

    return { currencies, values, conversion_value, show_box, get_values, get_latest_conversion, get_conversion_value, get_conversion_data, toggle_convert_box }
})
