<template>
    <component :is="SubjectManagerLayout">
        <v-form
            class="pa-6 bg-white"
            @submit.prevent="editSubject"
            :class="{ 'w-85': $vuetify.display.smAndDown }"
        >
            <p class="text-h1 font-weight-bold py-6">Upravit předmět</p>
            <div class="d-flex flex-column ga-2">
                <v-text-field
                    v-model="form.name"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                    :label="$t('global.name')"
                    :rules="[rules.required, rules.maxName]"
                ></v-text-field>
                <v-select
                    variant="outlined"
                    v-model="form.icon"
                    :items="icons"
                    item-title="iconName"
                    label="Ikona"
                >
                </v-select>
            </div>
            <v-btn
                type="submit"
                color="blue"
                class="d-flex justify-center margin-center"
                :class="{ 'w-100': $vuetify.display.smAndDown }"
            >
                {{ $t('global.edit') }}
            </v-btn>
        </v-form>
    </component>
</template>
<script setup>
import SubjectManagerLayout from '@/Frontend/layouts/SubjectManagerLayout.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import icons from '../../../itemsIcons'
import rules from './../../rules/rules'

const props = defineProps({ subject: Object })

const form = useForm({
    name: props.subject.name,
    icon: props.subject.icon
})

const editSubject = () => {
    form.put(route('subject.update', props.subject.id))
}
</script>

<style scoped lang="scss">
.v-form {
    width: 100%;
    max-width: 35em !important;
    border-radius: 1em;
    border: 1px solid gray;
    box-shadow: 1em 1em gray;
}

p:first-child {
    font-size: 1.8em !important;
}
</style>
