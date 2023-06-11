<template>
    <v-form @submit.prevent="login">
        <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">Přihlášení</h1>
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
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
                @click:append="show = !show"
            ></v-text-field>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                přihlásit!
            </v-btn>
        </v-container>
    </v-form>
</template>
<script setup>
import {ref} from "vue";

const show = ref(false);

/*const form = useForm({
    email: '',
    password: '',
})*/

const login = () => {
    //console.log(form)
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}
</style>
