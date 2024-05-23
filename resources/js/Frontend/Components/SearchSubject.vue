<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-text-field
                v-model="search"
                :disabled="disabled"
                v-bind="props"
                variant="outlined"
                class="search"
                hide-details
                prepend-inner-icon="mdi-folder-search-outline">
            </v-text-field>
        </template>

        <v-list>
            <v-list-item v-for="(item, index) in searchResult.search"
                         :key="index" class="py-0 px-0">
                <div class="py-2 px-4" v-if="searchResult.search.length > 0">
                    <Link v-if="item.slug" :href="route('subject.show', item.slug)">
                        <p class="text-subtitle-1">{{ item.name }}</p>
                    </Link>
                    <v-divider v-if="searchResult.search.length !== index + 1 "></v-divider>
                </div>
                <div class="text-center py-2 px-4 font-weight-bold" v-else>
                    {{ searchResult.search.not_found }}
                </div>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import axios from "axios";
import {ref, watch} from "vue";

defineProps({disabled: Boolean})

const search = ref('');
const searchResult = ref([]);

watch(search, async (val) => {
    if (val !== null) {
        await axios.get(`/dashboard/subject/search?search=${search.value}`)
            .then(response => {
                searchResult.value = response.data;
            })
    }
});
</script>

<style scoped lang="scss">
.v-text-field {
    max-width: 40em !important;
    align-self: normal;
}
</style>
