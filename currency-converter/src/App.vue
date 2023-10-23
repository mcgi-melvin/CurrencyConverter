<script setup>
import { RouterView } from 'vue-router'
import {app} from "@/stores/app";
import {onMounted} from "vue";

onMounted(() => app().get_data())
</script>

<template>
    <RouterView />
</template>

<style scoped>

</style>
