<template>
    <div class="d-flex justify-end align-center search" :class="{'justify-center pb-4': $vuetify.display.xs}">
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-text-field
                    v-model="search"
                    v-bind="props"
                    variant="outlined"
                    class="search"
                    hide-details
                    prepend-inner-icon="mdi-folder-search-outline">
                </v-text-field>
            </template>
            <v-list>
                <v-list-item v-for="(item, index) in searchResult.search"
                             :key="index" class="py-0 px-0" max-width="20em">
                    <div class="py-2 px-4" v-if="searchResult.search.length > 0">
                        <Link v-if="item.slug" :href="route('chapter.show', {slug: subject.slug, chapter: item.slug})">
                            <div class="text-4 font-weight-bold">{{ item.name }}</div>
                            <p class="text-subtitle-2">{{ item.perex }}</p>
                        </Link>
                        <v-divider v-if="searchResult.search.length !== index + 1 "></v-divider>
                    </div>
                    <div class="text-center py-2 px-4 font-weight-bold" v-else>
                        {{searchResult.search.item}}
                    </div>
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import axios from "axios";
import {Link} from "@inertiajs/inertia-vue3";

const props = defineProps({subject: Object})
const search = ref('');
const searchResult = ref([]);
watch(search, async (val) => {
    if (val !== null) {
        await axios.get(`/dashboard/manager/subject/${props.subject.slug}/select?select=${search.value}`)
            .then(response => {
                searchResult.value = response.data;
            })
    }
})
</script>

<style scoped lang="scss">
.search {
    width: 15em;
}
</style>
