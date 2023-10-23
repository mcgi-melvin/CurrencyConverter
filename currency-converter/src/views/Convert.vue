<template>
    <div :class="`convert-box ${convert_store.show_box ? 'show' : ''}`">
        <div class="main-wrapper">
            <div class="section-header">
                <div class="conversion-overview" v-if="conversion_currencies && conversion_currencies.length">
                    <h3>{{ conversion_currencies[0] }} 1 - {{ convert_store.get_latest_conversion( conversion_currencies ? conversion_currencies[0] : '', conversion_currencies[1]) }} {{ conversion_currencies[1] }}</h3>
                </div>
                <div class="input-fields">
                    <div class="input-field">
                        <label>{{ conversion_currencies[0] }}</label>
                        <input
                            type="number"
                            value="0"
                            min="1"
                            @keyup="convert_store.get_conversion_value( conversion_currencies[0], conversion_currencies[1] )"
                            @keydown="convert_store.get_conversion_value( conversion_currencies[0], conversion_currencies[1] )"
                            @change="convert_store.get_conversion_value( conversion_currencies[0], conversion_currencies[1] )"
                        />
                    </div>
                </div>
                <div class="input-value">
                    <h5>{{ conversion_currencies[1] }}</h5>
                    <h1>{{ conversion_currencies && conversion_currencies.length > 1 && isNaN( conversion_value ) ? 0 : conversion_value.toLocaleString('en-US', { style: 'currency', currency: conversion_currencies[1] || "USD" }) }}</h1>
                </div>
            </div>
            <div class="section-body">
                <Line id="convert_chart" :data="chart_data" :options="chart_options" />
            </div>
            <div class="section-footer">
                <a class="button button-black" href="javascript:void(0)" @click="convert_store.show_box = false">Close</a>
            </div>
        </div>
    </div>
</template>

<script>

import {defineComponent} from "vue";
import {Line} from "vue-chartjs";
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from "chart.js";
import {convert} from "@/stores/convert";

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default defineComponent({
    components: {Line},
    data() {
        return {
            convert_store: convert()
        }
    },
    computed: {
        conversion_currencies() {
            return this.convert_store.currencies
        },
        conversion_value() {
            return this.convert_store.conversion_value
        },
        chart_data() {
            let data = this.convert_store.get_conversion_data( this.conversion_currencies[0], this.conversion_currencies[1] ),
                labels = [],
                datasets = []



            return {
                labels,
                datasets
            }
        },
        chart_options() {
            return {}
        }
    }
})
</script>

<style scoped>
.convert-box {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease-in-out;
}

.convert-box .main-wrapper {
    display: flex;
    justify-content: space-between;
    flex-direction: column;
    width: 0;
    height: 0;
    max-width: 100%;
    max-height: 100%;
    overflow-y: auto;
    color: #000;
    background-color: #fff;
    transition: all 0.6s ease-in-out;
}

.convert-box .main-wrapper > div {
    padding: 30px;
}

.convert-box .main-wrapper .section-header .conversion-overview {
    text-align: center;
    margin: 0 0 20px;
}

.convert-box .main-wrapper .section-header .input-fields {
    display: flex;
    grid-gap: 20px;
}

.convert-box .main-wrapper .section-header .input-field {
    width: 100%;
    margin: 0 0 20px;
}

.convert-box .main-wrapper .section-header .input-field label {
    font-weight: 600;
}

.convert-box .main-wrapper .section-header .input-field input {
    font-size: 25px;
    width: 100%;
    height: 60px;
    padding: 12px 20px;
    border: none;
    outline: none;
    background-color: #eaeaea;
}

.convert-box .main-wrapper .section-footer {
    text-align: right;
}

.convert-box.show {
    visibility: visible;
    opacity: 1;
}

.convert-box.show .main-wrapper {
    width: 1200px;
    height: 800px;
}
</style>