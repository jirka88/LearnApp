<script setup>
import {markRaw, onMounted, ref} from "vue";
import {useUrlSearchParams} from "@vueuse/core";

const props = defineProps({disabled: Boolean});
const emit = defineEmits(['sort']);

const filtrValue = ref({state: 'Výchozí', id: 'default', sort: 'name'});

const items = markRaw(
    [{state: 'Výchozí', id: 'default', sort: 'name'},
        {state: 'Sestupně', id: 'asc', sort: 'name'},
        {state: 'Vzestupně', id: 'desc', sort: 'name'},
        {state: 'Od nejnovějších', id: 'asc', sort: 'created_at'},
        {state: 'Od nejstarších', id: 'desc', sort: 'created_at'}]
);

onMounted(() => {
    sort();
})
const sort = () => {
    const params = useUrlSearchParams('history')
    const sort = params.sort?.split(',');
    if (sort && sort.length === 2) {
        const sortValue = items.find(item => item.id === sort[sort.length - 1] && item.sort === sort[sort.length - 2]);
        filtrValue.value = sortValue;
    }
}

</script>

<template>
    <v-select
        v-model="filtrValue"
        @update:modelValue="emit('sort', filtrValue)"
        :items="items"
        :disabled="disabled"
        item-title="state"
        item-value="id"
        item-sort="sort"
        label="Výchozí"
        persistent-hint
        return-object
        hide-details
        single-line
        variant="outlined">

    </v-select>
</template>

<style scoped lang="scss">

</style>
