<template>
    <div class="d-flex justify-end align-center search" :class="{'justify-center pb-4': $vuetify.display.xs}">
        <v-autocomplete
            v-model="searchValue"
            :items="searchResult"
            :loading="isLoading"
            @update:search="updateUser"
            chips
            closable-chips
            hide-details
            hide-selected
            hide-no-data
            color="blue-grey-lighten-2"
            item-title="email"
            item-value="email"
            label="Výběr uživatele"
            multiple
            variant="outlined"
            hint="Zadejte jméno x přijmení x email uživatele"
        >
            <template v-slot:item="{ props, item }">
                <v-list-item
                    v-bind="props"
                    :prepend-avatar="item.raw.image ? '/storage/' + item.raw.image  : undefinedProfilePicture"
                    :title="item.raw.firstname + ' ' + item.raw.lastname"
                    :subtitle="item.raw.email"
                ></v-list-item>
            </template>
        </v-autocomplete>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import axios from "axios";
const emit = defineEmits(['getUser'])
const searchValue = ref([]);
const searchResult = ref([]);
const isLoading = ref(false);
import undefinedProfilePicture from './../../../assets/user/Default_pfp.svg';
const highlightText = (text) =>{
    const regex = new RegExp(searchValue.value, 'gi');
    return text.replace(regex,'<span style="background-color: #EFEFEF; ">$&</span>');
}

watch(searchValue, val => {
    emit('getUser', val)
})
const updateUser = (e) => {
    if (e !== null) {
        isLoading.value = true
        axios.get(`/dashboard/search/user?select=${e}`)
            .then(response => {
                searchResult.value = response.data;
            }).finally(() => {
            isLoading.value = false;
        })
    }
}
</script>

<style scoped lang="scss">
</style>
