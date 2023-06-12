<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            width="auto"
        >
            <Dialog v-if="dialog" @close="dialog = false" @disagree="confirm = false" @agree="confirm = true"></Dialog>
        </v-dialog>
    </v-row>
    <v-form @submit.prevent="register">
        <v-container class="d-flex flex-column pa-3 w-75 gap-2" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">Registrace</h1>
            <v-text-field
                v-model="form.firstname"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :counter="15"
                label="Jméno"
                :rules="[rules.required]"
                :error="form.errors.firstname"
                required/>
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                :rules="[rules.required]"
                required
            ></v-text-field>
            <v-text-field
                v-model="form.password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                name="input-10-1"
                label="Heslo"
                :rules="[rules.required]"
                :error="form.errors.password"
                @click:append="show = !show"
            ></v-text-field>
            <v-text-field
                v-model="passwordRc"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show1 ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                name="input-10-1"
                label="Potvrzení hesla"
                :rules="[rules.required]"
                :error="form.errors.password_confirm"
                @click:append="show1 = !show1"
            ></v-text-field>
            <div class="d-flex">
            <v-checkbox :rules="[rules.required]" v-model="confirm" @click="setDialog" label="Souhlas se zpracováním osobních údajů"></v-checkbox>
            </div>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                Registrovat!
            </v-btn>
        </v-container>
    </v-form>

</template>

<script setup>

import {ref} from "vue";
import {defineAsyncComponent} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
const show = ref('');
const show1 = ref('');
const confirm = ref(false);
const passwordRc = ref('');
const dialog = ref(false);
const Dialog = defineAsyncComponent(() => import('./Dialog.vue'));

defineProps({ errors: Object })

const form = useForm({
    firstname: '',
    email: '',
    password: '',
    password_confirm: ''
});

const rules = {
    required: value => !!value || 'Požadované!'
}
const setDialog = () =>{
    dialog.value = !dialog.value;
}
const register = () => {
    console.log(form)
    form.post(route('register'));
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}
</style>
