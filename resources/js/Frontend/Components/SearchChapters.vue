
<template>
    <div class="d-flex justify-end align-center search" :class="{'justify-center pb-4': $vuetify.display.xs}">
        <v-autocomplete
            v-model="search"
            variant="outlined"
            :items="selectedChapters"
            item-title="name"
            item-value="id"
            class="search"
            hide-details
            prepend-inner-icon="mdi-folder-search-outline">
        </v-autocomplete>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import axios from "axios";

const props = defineProps({ subject: Object, selectedChapters: Object})
const search = ref('');
watch(search, async(val) =>{
    if(val !== null) {
        await axios.get(`/dashboard/manager/subject/${props.subject.slug}/select?select=${search.value}`)
            .then(response => {
                 console.log(response.data);
            })
    }
})

/*watch(props.selectedChapter, async () => {
    props.selectedChapterShow.value = props.chapters.find(x => x.name === props.selectedChapter.value);
    if (selectedChapterShow.value === undefined) {
        await axios.get(`/dashboard/manager/subject/${props.subject.slug}/select?select=${selectedChapter.value}`)
            .then(response => {
                selectedChapterShow.value = response.data;
            })
            .catch(error => {
                console.error(error);
            });

        if(selectedChapterShow.value.loadedSelectedChapter === null)  {
            return;
        }
    }
    router.push(`?select=${selectedChapter.value}`);
});*/
</script>

<style scoped lang="scss">
.search {
    width: 15em;
}
</style>
