

<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">Resetování hesla:</legend>
        <v-form ref="formResetPassword" @submit.prevent="changePassword">
            <v-text-field v-model="formPassword.oldPassword" label="Staré heslo" :rules="[rules.required]"
                          variant="outlined"
                          prepend-inner-icon="mdi-lock"></v-text-field>
            <v-text-field v-model="formPassword.newPassword" label="Nové heslo"
                          :rules="[rules.required, rules.password]" variant="outlined"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          prepend-inner-icon="mdi-lock"
                          :type="show1 ? 'text' : 'password'"
                          @click:append="show1 = !show1"
            >

            </v-text-field>
            <v-text-field v-model="formPassword.againNewPassword" label="Nové heslo znova"
                          :rules="[rules.required, rules.passwordConfirm]"
                          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show2 ? 'text' : 'password'"
                          prepend-inner-icon="mdi-lock"
                          @click:append="show2 = !show2"
                          variant="outlined"></v-text-field>
            <p class="text-center text-red">{{ props.errors.msg }}</p>
            <p v-if="$page.props.flash.messagePasswordReset" class="text-center text-green">
                {{ $page.props.flash.messagePasswordReset }}</p>
            <v-btn type="submit"
                   color="blue"
                   class="btn d-flex"
                   :class="{'w-100': $vuetify.display.smAndDown}"
            >
                Změnit heslo!
            </v-btn>
        </v-form>
    </fieldset>
</template>
<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

const show1 = ref('');
const show2 = ref('');
const props = defineProps({'usr': Object, errors: Object})

const formPassword = useForm({
    oldPassword: '',
    newPassword: '',
    againNewPassword: '',
})
const changePassword = async () => {
    formPassword.post('/dashboard/user/changePassword', {
        onSuccess: () => {
            formPassword.reset();
        }
    });
}
const rules = {
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
    margin: 1em auto;
}
</style>
