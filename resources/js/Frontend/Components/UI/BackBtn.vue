<script setup>

import {Link} from "@inertiajs/inertia-vue3";
defineProps({url: Object})
</script>

<template>
    <v-col cols="auto">
        <Link :href="url">
            <v-btn icon="mdi-subdirectory-arrow-left" size="large">
            </v-btn>
        </Link>
    </v-col>
</template>

<style scoped lang="scss">

</style>
