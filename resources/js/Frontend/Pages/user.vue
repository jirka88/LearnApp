<template>
    <AdminLayout>
        <p>{{user}}</p>
    </AdminLayout>

</template>
<script setup>
defineProps({user: Object})
import AdminLayout from "./../layouts/AdminLayout.vue";

</script>
<style scoped lang="scss">

</style>
