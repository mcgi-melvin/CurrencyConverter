<template>
    <div class="container">
        <h1>Currency Converter</h1>
        <RouterView />
    </div>
</template>

<script setup>
import { RouterView } from "vue-router";
</script>

<style scoped>
.container {
    max-width: 1600px;
    width: 100%;
    margin: auto;
}

h1 {
    text-align: center;
}
</style>