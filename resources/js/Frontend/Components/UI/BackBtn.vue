<script setup>

import {Link} from "@inertiajs/inertia-vue3";
defineProps({url: Object, background: String})
</script>

<template>
        <Link :href="url">
            <v-btn icon="mdi-subdirectory-arrow-left" size="large" :color="background">
            </v-btn>
        </Link>
</template>

<style scoped lang="scss">

</style>
