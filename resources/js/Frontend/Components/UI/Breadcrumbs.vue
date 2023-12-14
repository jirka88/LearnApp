<template>
 <v-breadcrumbs :items="rewriteItems" class="pa-0 pt-4">
        <template v-slot:divider>
                <v-icon icon="mdi-chevron-right">

                </v-icon>
        </template>
        <template v-slot:prepend>
            <Link :href="route('dashboard')">
                <v-icon size="small" icon="mdi-home">

                </v-icon>
            </Link>
        </template>

    </v-breadcrumbs>
</template>
<script setup>
import { ref} from "vue";
import { Link, useInertia } from '@inertiajs/inertia-vue3';
const props = defineProps({items: Array})

const rewriteItems = ref(props.items);
rewriteItems.value.unshift( { title: '' });

</script>
<style scoped lang="scss">
.v-breadcrumbs {
    flex-wrap: wrap;
}
:deep(.v-breadcrumbs-item:last-child){
    text-decoration: underline !important;
}
</style>
