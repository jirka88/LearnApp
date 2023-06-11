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
    <v-form>
        <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">Registrace</h1>
            <v-text-field
                v-model="firstnameR"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :counter="15"
                label="Jméno"
                required/>
            <v-text-field
                v-model="emailR"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                required
            ></v-text-field>
            <v-text-field
                v-model="passwordR"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                name="input-10-1"
                label="Heslo"
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
                @click:append="show1 = !show1"
            ></v-text-field>
            <div class="d-flex">
            <v-checkbox v-model="confirm" @click="setDialog" label="Souhlas se zpracováním osobních údajů"></v-checkbox>
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
const show = ref('');
const show1 = ref('');
const confirm = ref(false);
const firstnameR = ref('');
const emailR = ref('');
const passwordR = ref('');
const passwordRc = ref('');
const dialog = ref(false);

const Dialog = defineAsyncComponent(() => import('./Dialog.vue'));
const setDialog = () =>{
    dialog.value = !dialog.value;
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}
</style>
