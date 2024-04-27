<template>
<component :is="SubjectManagerLayout">
    <div class="create-subject d-flex justify-center align-center"
         :class="{'w-85': $vuetify.display.smAndDown,
                            'w-95': $vuetify.display.xs}">
        <v-form class="pa-6 bg-white" @submit.prevent="editSubject" >
            <p class="text-h1 font-weight-bold">Upravit předmět</p>
            <v-text-field
                v-model="form.name"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                :label="$t('global.name')"
                :rules="[rules.required, rules.minName, rules.maxName]"
            ></v-text-field>
            <v-select
                variant="outlined"
                v-model="form.icon"
                :items="icons"
                item-title="iconName"
                label="Ikona">
            </v-select>

            <v-btn
                type="submit"
                color="blue"
                class="d-flex justify-center margin-center"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                {{$t('global.edit')}}
            </v-btn>
        </v-form>
    </div>
</component>
</template>
<script setup>
import SubjectManagerLayout from "@/Frontend/layouts/SubjectManagerLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import icons from "../../../itemsIcons";
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
const props = defineProps({subject: Object})

const form = useForm({
    name: props.subject.name,
    icon: props.subject.icon
});

const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    minName: v => v.length > 3 || 'Předmět musí mít delší název!',
    maxName: v => v.length < 25 ||'Předmět nesmí být delší!'
}

const editSubject = () => {
    form.put(route('subject.update', props.subject.id));
}
</script>

<style lang="scss">

</style>
