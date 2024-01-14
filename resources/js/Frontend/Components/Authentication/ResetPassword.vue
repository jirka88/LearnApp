
<template>
    <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
        <h1 class="pa-5 text-center">Reset hesla</h1>
        <v-text-field
            v-model="form.email"
            prepend-inner-icon="mdi-email"
            variant="outlined"
            label="E-mail"
            :rules="[rules.required, rules.email]"
            required
        ></v-text-field>
        <v-btn
            type="submit"
            color="blue"
            class="mt-2 margin-center"
            :disabled="off"
            :class="{'w-100': $vuetify.display.smAndDown}"
        >
            Reset
        </v-btn>
    </v-container>
</template>
<script setup>
import {ref} from "vue";
const off = ref(false);
const rules = {
    required: v => !!v || 'Nutné vyplnit!',
    email: v => /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || 'Musí být platná e-mailová adresa!',
}
const form = {
    email: ''
}
</script>
<style scoped lang="scss">

</style>
