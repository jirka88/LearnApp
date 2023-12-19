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
        <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">Registrace</h1>
            <v-text-field
                v-model="form.firstname"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :label="$t('authentication.register.name')"
                :rules="[rules.required, rules.firstnameLength]"
                required/>
            <v-text-field
                v-model="form.lastname"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :label="$t('authentication.register.surname')"
                :rules="[rules.required, rules.lastnameLength]"
                required/>
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                :rules="[rules.email, rules.required]"
                required
            ></v-text-field>
            <v-text-field
                v-model="form.password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                :label="$t('authentication.register.password')"
                :rules="[rules.required, rules.password]"
                @click:append="show = !show"
            ></v-text-field>
            <v-text-field
                v-model="form.password_confirm"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show1 ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                :label="$t('authentication.register.password_confirm')"
                :rules="[rules.required, rules.passwordConfirm]"
                @click:append="show1 = !show1"
            ></v-text-field>
            <v-select
                v-model="form.type"
                label="Typ účtu"
                :items="items"
                item-title="state"
                item-value="value"
                variant="outlined"
                persistent-hint
                return-object
                hide-details
                single-line
            ></v-select>
            <div class="d-flex">
            <v-checkbox v-model="form.confirm" @click="setDialog" label="Souhlas se zpracováním osobních údajů" hide-details></v-checkbox>
            </div>
            <span class="text-center text-red">{{form.errors.confirm}}</span>
            <span class="text-center text-red">{{form.errors.email !== "0" ? form.errors.email : ''}}</span>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :disabled="off"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                Registrovat!
            </v-btn>
        </v-container>
    </v-form>

</template>

<script setup>

import {markRaw, ref} from "vue";
import {defineAsyncComponent} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
const off = ref(false);
const show = ref('');
const show1 = ref('');
const confirm = ref(false);
const dialog = ref(false);
const Dialog = defineAsyncComponent(() => import('./Dialog.vue'));

const items = markRaw([
    {state: 'Osobní účet', value: '1'},
    {state: 'Školní účet', value: '2'}]
);


defineProps({ errors: Object })

const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    type: {state: 'Osobní účet', value: '1'},
    password_confirm: '',
    confirm: ''
});

const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    firstnameLength: v => v.length < 25 || 'Jméno je příliš dlouhé!',
    lastnameLength: v => v.length < 50 ||'Příjmení je příliš dlouhé!',
    email: v => /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || 'E-mail musí být validní!',
    password: v => {
        const missingElements = [];
        if(v.length < 8) {
            missingElements.push('více než 8 znaků');
        }
        if (!/(?=.*\d)/.test(v)) {
            missingElements.push('číslici');
        }
        if (!/[!@#$%^&*]/.test(v)) {
            missingElements.push('speciální znak');
        }
        if (!/(?=.*[a-z])/.test(v)) {
            missingElements.push('malé písmeno');
        }
        if (!/(?=.*[A-Z])/.test(v)) {
            missingElements.push('velké písmeno');
        }
        if (missingElements.length > 0) {
            return `Heslo musí obsahovat ${missingElements.join(', ')}!`;
        }
        else {
            return true;
        }
    },
    passwordConfirm: v => v === form.password || "Hesla se neshodují!"
}
const setDialog = () =>{
        dialog.value = !dialog.value;
}
const register = () => {
    off.value = true
    form.post(route('register'));
    off.value = false
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}
:deep(.v-messages__message)  {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
