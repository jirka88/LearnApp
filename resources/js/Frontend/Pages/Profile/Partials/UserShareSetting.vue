<template>
    <v-sheet class="pa-8" rounded="xl" elevation="2">
        <v-form @submit.prevent="form.put(route('user.share', { id: usr.id }))">
            <v-select
                v-model="form.share"
                :items="items"
                item-title="state"
                item-value="id"
                hint="Umožnit přijímat nasdílení."
                persistent-hint
                return-object
                single-line
                variant="outlined"
            ></v-select>
            <BtnSetting></BtnSetting>
        </v-form>
    </v-sheet>
</template>
<script setup>
import { markRaw } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import BtnSetting from '@/Frontend/Components/BtnSetting.vue'

const props = defineProps({ usr: Object })

const items = markRaw([
    { state: 'ANO', id: 1 },
    { state: 'NE', id: 0 }
])

const form = useForm({
    share: items.find((el) => el.id === props.usr.canShare)
})
</script>
<style scoped lang="scss"></style>
