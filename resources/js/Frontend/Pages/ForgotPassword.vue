<template>
    <Layout>
        <v-container>
            <div class="menu justify-center align-center">
                <v-sheet class="pa-8" rounded="" elevation="10">
                    <h1 class="py-4">Resetování hesla</h1>
                    <v-form @submit.prevent="changePassword" class="d-flex flex-column ga-1">
                        <v-text-field
                            v-model="form.email"
                            prepend-inner-icon="mdi-email"
                            variant="outlined"
                            label="E-mail"
                            :rules="[rules.required, rules.email]"
                            required
                            autofocus
                        ></v-text-field>
                        <v-text-field v-model="form.password"
                                      :label="$t('userAccount.new_password')"
                                      :rules="[rules.password]"
                                      variant="outlined"
                                      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                      prepend-inner-icon="mdi-lock"
                                      :type="show1 ? 'text' : 'password'"
                                      @click:append="show1 = !show1"
                        >
                        </v-text-field>
                        <v-text-field v-model="form.password_confirmation"
                                      :label="$t('userAccount.confirm_password')"
                                      :rules="[customRules.passwordConfirm]"
                                      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                      :type="show2 ? 'text' : 'password'"
                                      prepend-inner-icon="mdi-lock"
                                      @click:append="show2 = !show2"
                                      variant="outlined"></v-text-field>
                        <v-btn type="submit"
                               color="blue"
                               class="btn d-flex"
                               :disabled="disabled"
                               :class="{'w-100': $vuetify.display.smAndDown}"
                        >
                            {{ $t('userAccount.change_password') }}!
                        </v-btn>
                    </v-form>
                </v-sheet>
            </div>
        </v-container>
    </Layout>
</template>
<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

const show = ref('');
const show1 = ref('');
const show2 = ref('');
const props = defineProps({token: String})
import rules from './../rules/rules'
import Layout from "@/Frontend/layouts/AuthLayout.vue";

const customRules = {
    passwordConfirm: v => v === form.password || "Hesla se neshodují!",
}
const disabled = ref(false);
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    token: props.token
})
const changePassword = async () => {
    disabled.value = true;
    form.post(route('reset'), {
        onSuccess: () => {
            disabled.value = false;
        },
    });
}
</script>
<style scoped lang="scss">
.v-btn {
    padding: 1.6em;
    margin: 1em auto;
}

.menu {
    display: flex;
    min-height: 100vh;

    .v-sheet {
        min-width: 40em !important;
    }
}
</style>
