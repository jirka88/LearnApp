<template>
    <v-form @submit.prevent="login">
        <v-container class="d-flex flex-column pa-3 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
            <h1 class="pa-5 text-center">{{$t('authentication.login')}}</h1>
            <v-text-field
                v-model="form.email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                label="E-mail"
                :rules="[rules.required]"
                required
            ></v-text-field>
            <v-text-field
                v-model="form.password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                variant="outlined"
                name="input-10-1"
                :rules="[rules.required]"
                :label="$t('authentication.register.password')"
                hide-details
                @click:append="show = !show"
            ></v-text-field>
            <div class="d-flex justify-center align-center">
            <v-checkbox
                v-model="form.remember"
                hide-details
                color="blue"
                :label="$t('authentication.remember')">

            </v-checkbox>
            <Link class="forgetPassword" href="" >
                {{$t('authentication.forget')}}
            </Link>
            </div>
            <span class="text-center text-red pa-2">{{form.errors.msg}}</span>
            <v-btn
                type="submit"
                color="blue"
                class="mt-2"
                :disabled="off"
                :class="{'w-100': $vuetify.display.smAndDown}"
            >
                {{$t('authentication.loginBtn')}}
            </v-btn>
        </v-container>
    </v-form>
</template>
<script setup>
import {ref} from "vue";
import {useForm, Link} from "@inertiajs/inertia-vue3";

defineProps({ errors: Object })

const show = ref(false);
const off = ref(false);

const rules = {
    required: v => !!v || 'Nutné vyplnit!',
}

const form = useForm({
    email: '',
    password: '',
    remember: '',
})

const login = () => {
    off.value = true;
    form.post(route('login'));
    off.value = false;
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
.forgetPassword {
    color: gray;
    position: relative;
    &::before {
        position: absolute;
        content: "";
        height: 2px;
        width: 0%;
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
