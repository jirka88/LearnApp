<template>
    <Dialog
        v-if="dialog"
        v-model:active="dialog"
        @accepted="confirmRules"
    ></Dialog>
    <v-form @submit.prevent="register">
        <v-container
            class="d-flex flex-column pa-3 w-75"
            :class="{ 'w-100': $vuetify.display.mdAndDown }"
        >
            <h1 class="pa-5 text-center">
                {{ $t('authentication.register.register') }}
            </h1>
            <v-text-field
                v-model="form.firstname"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :label="$t('authentication.register.name')"
                :rules="[rules.required, rules.firstnameLength]"
                :error="form.errors.firstname"
                :error-messages="form.errors.firstname?.min"
                @input="
                    form.errors.firstname ? delete form.errors.firstname : ''
                "
                autofocus
                required
            />
            <v-text-field
                v-model="form.lastname"
                prepend-inner-icon="mdi-account"
                variant="outlined"
                :label="$t('authentication.register.surname')"
                :rules="[rules.required, rules.lastnameLength]"
                required
            />
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                :rules="[rules.email, rules.required, rules.emailLength]"
                :error="form.errors.email?.unique"
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
                :rules="[rules.required, customRules.passwordConfirm]"
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
                <v-checkbox
                    v-model="form.confirm"
                    @click="setDialog"
                    label="Souhlas se zpracováním osobních údajů"
                    hide-details
                ></v-checkbox>
            </div>

            <span class="text-center text-red">{{ form.errors.confirm }}</span>
            <Toastify
                v-if="isActiveToast"
                :text="form.errors.email?.unique ?? form.errors.token"
                variant="error"
                :time="3000"
                @close="isActiveToast = false"
            ></Toastify>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :disabled="off"
                :class="{ 'w-100': $vuetify.display.smAndDown }"
            >
                Registrovat!
            </v-btn>
            <p class="mt-4">
                Tato stránka je chráněna reCAPTCHA a Google
                <a
                    class="text-decoration-underline text-cyan"
                    href="https://policies.google.com/privacy"
                    >Privacy Policy</a
                >
                a
                <a
                    class="text-decoration-underline text-cyan"
                    href="https://policies.google.com/terms"
                    >Terms of Service</a
                >
                potvrzení.
            </p>
        </v-container>
    </v-form>
</template>

<script setup>
import { markRaw, ref } from 'vue'
import { defineAsyncComponent } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'

const off = ref(false)
const show = ref('')
const show1 = ref('')
const dialog = ref(false)
const Dialog = defineAsyncComponent(() => import('../DialogAgree.vue'))
import { isActiveToast, toastShow } from '@/Toast'
import Toastify from '@/Frontend/Components/UI/Toastify.vue'
import { useReCaptcha } from 'vue-recaptcha-v3'
import rules from './../../rules/rules'

const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

const items = markRaw([
    { state: 'Osobní účet', value: '1' },
    { state: 'Školní účet', value: '2' }
])

defineProps({ errors: Object })
const customRules = {
    passwordConfirm: (v) => v === form.password || 'Hesla se neshodují!'
}
const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    type: { state: 'Osobní účet', value: '1' },
    password_confirm: '',
    confirm: '',
    token: ''
})

const setDialog = () => {
    dialog.value = !dialog.value
}
const register = async () => {
    await recaptchaLoaded()
    const token = await executeRecaptcha('login')
    form.token = token
    off.value = true
    form.post(route('register'), {
        onError: () => {
            if (form.errors?.email?.unique !== undefined || form.errors.token) {
                toastShow(true)
            }
        }
    })
    off.value = false
}

const confirmRules = (val) => {
    form.confirm = val
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}

:deep(.v-messages__message) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
