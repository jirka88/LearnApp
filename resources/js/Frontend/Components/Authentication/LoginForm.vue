<template>
    <v-form @submit.prevent="login">
        <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">{{ $t('authentication.login') }}</h1>
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                :rules="[rules.required, rules.email]"
                required
                autofocus
            ></v-text-field>
            <v-text-field
                v-model="form.password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                :rules="[rules.required]"
                :label="$t('authentication.register.password')"
                @click:append="show = !show"
            ></v-text-field>
            <div class="d-flex justify-center align-center">
                <v-checkbox
                    v-model="form.remember"
                    hide-details
                    color="blue"
                    :label="$t('authentication.remember')">

                </v-checkbox>
                <Link class="forgetPassword" :href="route('reset')">
                    {{ $t('authentication.forget') }}
                </Link>
            </div>
            <Toastify v-if="isActiveToast" :text="form.errors.msg" variant="error" :time="5000"
                      @close="toastShow(false)"></Toastify>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :disabled="off"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                {{ $t('authentication.loginBtn') }}
            </v-btn>
        </v-container>
    </v-form>
</template>
<script setup>
import {ref} from "vue";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import {isActiveToast, toastShow} from "@/Toast";
import Toastify from "@/Frontend/Components/UI/Toastify.vue";
import rules from "./../../rules/rules"
defineProps({errors: Object})

const show = ref(false);
const off = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: '',
})


const login = () => {
    off.value = true;
    form.post(route('login'), {
        onError: () =>{
            if(form.errors.msg !== undefined) {
                toastShow(true)
            }
        }
    });
    off.value = false;
}
</script>

<style scoped lang="scss">
.v-form {
    .v-input:nth-child(2) {
        padding-bottom: 1em !important;
    }
    :deep(.v-input__details):not(:has(.v-messages__message)) {
        display: none;
    }
    .v-input:last-child :deep(.v-input__details) {
        padding: 0 !important;
    }
    .v-btn {
        margin: 0 auto;
    }
}
.forgetPassword {
    color: gray;
    position: relative;

    &::before {
        position: absolute;
        content: "";
        height: 2px;
        width: 0;
        background: black;
        bottom: 0;
    }

    &:hover {
        color: black;
        transition: 0.3s;
    }

    &:hover::before {
        width: 100%;
        transition: 0.3s;
    }
}

</style>
