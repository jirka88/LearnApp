

<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">{{$t('userAccount.password_reset')}}:</legend>
        <v-form ref="formResetPassword" @submit.prevent="changePassword">
            <v-text-field v-model="formPassword.oldPassword" :label="$t('userAccount.old_password')" :rules="[rules.oldPassword]"
                          hint="Staré heslo, které jste zadal při registraci."
                          variant="outlined"
                          prepend-inner-icon="mdi-lock"></v-text-field>
            <v-text-field v-model="formPassword.newPassword" :label="$t('userAccount.new_password')"
                          :rules="[rules.password]" variant="outlined"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          prepend-inner-icon="mdi-lock"
                          :type="show1 ? 'text' : 'password'"
                          @click:append="show1 = !show1"
            >

            </v-text-field>
            <v-text-field v-model="formPassword.againNewPassword" :label="$t('userAccount.confirm_password')"
                          :rules="[rules.passwordConfirm]"
                          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show2 ? 'text' : 'password'"
                          prepend-inner-icon="mdi-lock"
                          @click:append="show2 = !show2"
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

const show1 = ref('');
const show2 = ref('');
const props = defineProps({'usr': Object, errors: Object})
import {isActiveToast, statusToast, toastShow, toastStatus} from "@/Toast";

const formPassword = useForm({
    oldPassword: '',
    newPassword: '',
    againNewPassword: '',
})
const changePassword = async () => {
    formPassword.put('/dashboard/user/changePassword', {
        onSuccess: () => {
            toastShow(true);
            toastStatus(true);
            formPassword.reset();
        },
        onError: () => {
            toastShow(true);
            toastStatus(false);
        }
    });
}
const rules = {
    oldPassword: v => v.length > 0 || "Nutné zadat staré heslo!",
    password: v => {
        const missingElements = [];
        if (v.length < 8) {
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
    },
    passwordConfirm: v => v === formPassword.newPassword || "Hesla se neshodují!",
}
</script>
<style scoped lang="scss">
.v-btn {
    padding: 1.6em;
    margin: 1em auto;
}
</style>
