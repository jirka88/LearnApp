<template>
    <v-breadcrumbs :items="rewriteItems" class="pa-0 pt-4">
        <template v-slot:divider>
            <v-icon icon="mdi-chevron-right"></v-icon>
        </template>
        <template v-slot:prepend>
            <Link :href="route('dashboard')">
                <v-icon size="small" icon="mdi-home"></v-icon>
            </Link>
        </template>
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item
                class="text-subtitle-2 crumb-item"
                :disabled="item.disabled"
                exact
            >
                <Link v-if="item.to" :href="route(item.to)"
                    >{{ item.title }}
                </Link>
                <p v-else>{{ item.title }}</p>
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>
</template>
<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({ items: Array })
const rewriteItems = computed(() => {
    const items = [...props.items]
    items.unshift({ title: '' })
    return items
})
</script>
<style scoped lang="scss">
.v-breadcrumbs {
    flex-wrap: wrap;
}

:deep(.v-breadcrumbs-item:last-child) {
    text-decoration: underline !important;
}
</style>
