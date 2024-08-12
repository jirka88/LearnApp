<template>
    <component :is="DashboardLayout">
        <div class="report d-flex justify-center align-center">
            <v-form
                class="pa-6 d-flex flex-column overflow-auto"
                @submit.prevent="report"
                :class="{ 'w-85': $vuetify.display.smAndDown }"
            >
                <p class="text-h1 font-weight-bold text-no-wrap">
                    Náhlásit chybu
                </p>
                <v-textarea
                    v-model="form.name"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                    label="Popište chybu"
                    :rules="[rules.required, rules.maxName]"
                ></v-textarea>
                <v-btn
                    type="submit"
                    color="red"
                    class="d-flex justify-center margin-center"
                    :class="{ 'w-100': $vuetify.display.smAndDown }"
                >
                    Odeslat!
                </v-btn>
            </v-form>
        </div>
    </component>
</template>
<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '../layouts/DashboardLayout.vue'

const rules = {
    required: (value) => !!value || 'Nutné vyplnit!',
    maxName: (v) => v.length < 1200 || 'Předmět nesmí být delší!'
}

const form = useForm({
    name: ''
})

const report = () => {}
</script>
<style scoped lang="scss">
.report {
    height: calc(100vh - 64px);
    gap: 2em;
    .v-form {
        width: 900px;
        border-radius: 1em;
        border: 1px solid gray;
        box-shadow: 1em 1em gray;
        .text-h1 {
            font-size: 2em !important;
        }
    }
}
:deep(.v-messages__message) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
