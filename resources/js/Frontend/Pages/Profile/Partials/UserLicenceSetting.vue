<script setup>
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({ usr: Object, licences: Array })
const form = useForm({
    licences: {
        Licence: props.usr.licences.Licence,
        id: props.usr.licences.id
    }
})
</script>

<template>
    <v-sheet class="pa-8" rounded="xl" elevation="2">
        <v-form v-if="$page.props.permission.view">
            <div class="text-subtitle-1 text-medium-emphasis">Licence:</div>
            <v-select
                v-model="form.licences"
                :items="licences"
                item-title="Licence"
                item-value="id"
                label="Select"
                persistent-hint
                return-object
                single-line
                variant="outlined"
            ></v-select>
        </v-form>
        <div v-else>
            <div>Licence:</div>
            <div
                v-if="!$page.props.permission.view"
                class="w-50 font-weight-bold pt-2 py-6"
            >
                {{ props.usr.licences.Licence }}
            </div>
        </div>
    </v-sheet>
</template>

<style scoped lang="scss"></style>
