

<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">{{$t('userAccount.password_reset')}}:</legend>
        <v-form ref="formResetPassword" @submit.prevent="changePassword">
            <v-text-field v-model="formPassword.oldPassword"
                          :label="$t('userAccount.old_password')"
                          :rules="[rules.oldPassword]"
                          hint="Staré heslo, které jste zadal při registraci."
                          variant="outlined"
                          :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                          prepend-inner-icon="mdi-lock"
                          :type="show ? 'text' : 'password'"
                          @click:append="show = !show"
                          :error="formPassword.errors.oldPassword"
                          :error-messages="formPassword.errors.oldPassword"></v-text-field>
            <v-text-field v-model="formPassword.newPassword"
                          :label="$t('userAccount.new_password')"
                          :rules="[rules.password]"
                          variant="outlined"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          prepend-inner-icon="mdi-lock"
                          :type="show1 ? 'text' : 'password'"
                          :error="formPassword.errors.newPasswordSameAsOld"
                          :error-messages="formPassword.errors.newPasswordSameAsOld"
                          @click:append="show1 = !show1"
            >

            </v-text-field>
            <v-text-field v-model="formPassword.againNewPassword"
                          :label="$t('userAccount.confirm_password')"
                          :rules="[customRules.passwordConfirm]"
                          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show2 ? 'text' : 'password'"
                          prepend-inner-icon="mdi-lock"
                          @click:append="show2 = !show2"
                          :error="formPassword.errors.againNewPassword"
                          :error-messages="formPassword.errors.againNewPassword"
                          variant="outlined"></v-text-field>
            <p class="text-center text-red">{{ props.errors.msg }}</p>
            <Toastify v-if="isActiveToast" :text="statusToast ? $page.props.flash.message : 'Nastala chyba!'" :variant="statusToast ? 'success' : 'error'" :time="3000" @close="isActiveToast = false"></Toastify>
            <v-btn type="submit"
                   color="blue"
                   class="btn d-flex"
                   :class="{'w-100': $vuetify.display.smAndDown}"
            >
                {{$t('userAccount.change_password')}}!
            </v-btn>
        </v-form>
    </fieldset>
</template>
<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Toastify from "@/Frontend/Components/UI/Toastify.vue";
const show = ref('');
const show1 = ref('');
const show2 = ref('');
const props = defineProps({'usr': Object, errors: Object})
import {isActiveToast, statusToast, toastShow, toastStatus} from "@/Toast";
import rules from './../rules/rules'
const customRules = {
    passwordConfirm: v => v === form.password || "Hesla se neshodují!",
}
const formPassword = useForm({
    oldPassword: '',
    newPassword: '',
    againNewPassword: '',
})
const changePassword = async () => {
    formPassword.put('/dashboard/user/changePassword', {
        onSuccess: () => {
            formPassword.reset();
            toastStatus(true);
        },
        onError: () => {
            toastStatus(false);
        },
        onFinish: () => {
            toastShow(true);
        }
    });
}
</script>
<style scoped lang="scss">
.v-btn {
    padding: 1.6em;
    margin: 1em auto;
}
</style>
