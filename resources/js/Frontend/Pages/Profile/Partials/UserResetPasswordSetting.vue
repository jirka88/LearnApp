<template>
    <v-sheet class="pa-8" rounded="xl" elevation="2">
        <v-form @submit.prevent="changePassword">
            <v-text-field
                v-model="formPassword.oldPassword"
                :label="$t('userAccount.old_password')"
                :rules="[rules.oldPassword]"
                hint="Staré heslo, které jste zadal při registraci."
                variant="outlined"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                prepend-inner-icon="mdi-lock"
                :type="show ? 'text' : 'password'"
                @click:append="show = !show"
                :error="formPassword.errors.oldPassword"
                :error-messages="formPassword.errors.oldPassword"
            ></v-text-field>
            <v-text-field
                v-model="formPassword.newPassword"
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
            <v-text-field
                v-model="formPassword.againNewPassword"
                :label="$t('userAccount.confirm_password')"
                :rules="[customRules.passwordConfirm]"
                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show2 ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                @click:append="show2 = !show2"
                :error="formPassword.errors.againNewPassword"
                :error-messages="formPassword.errors.againNewPassword"
                variant="outlined"
            ></v-text-field>
            <BtnSetting></BtnSetting>
        </v-form>
    </v-sheet>
</template>
<script setup>
import { ref } from 'vue'
import rules from './../../../rules/rules'
import { useForm } from '@inertiajs/inertia-vue3'
import BtnSetting from '@/Frontend/Components/BtnSetting.vue'
const props = defineProps({ usr: Object, errors: Object })
const show = ref('')
const show1 = ref('')
const show2 = ref('')

const customRules = {
    passwordConfirm: (v) => v === formPassword.newPassword || 'Hesla se neshodují!'
}
const formPassword = useForm({
    oldPassword: '',
    newPassword: '',
    againNewPassword: ''
})
const changePassword = () => {
    formPassword.put('/dashboard/user/password', {
        onSuccess: () => {
            formPassword.reset()
        }
    })
}
</script>
<style scoped lang="scss">
:deep(.v-input__details) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
