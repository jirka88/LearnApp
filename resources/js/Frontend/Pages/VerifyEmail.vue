<script setup>
import axios from 'axios';
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {ref} from "vue";
import emailVerifyIcon from "./../../../../resources/assets/email/email-verify.svg"

const disabledBtn = ref(false);
const message = ref('');

const verifyEmail = () => {
    disabledBtn.value = true;
    axios.post('/email/request-verification')
        .then(response => {
            message.value = response.data.message
            disabledBtn.value = false;
        })
        .catch(error => {
            disabledBtn.value = false;
        });
}
</script>

<template>
    <component :is="DashboardLayout">
        <v-container class="px-4 d-flex justify-center align-center vh-calc ">
            <v-sheet max-width="36em" rounded elevation="10" class="text-center pa-8">
                <v-img max-height="8em" :src="emailVerifyIcon"></v-img>
                <p class="text-h4 font-weight-bold">Ověřte svojí emailovou adresu</p>
                <p class="py-6">Zaslali jsme Vám odkaz na email <span class="font-weight-bold">{{
                        $page.props.user.email
                    }}</span></p>
                <p>Klikněte na link v emailu pro ověření Vaší emailové adresy.</p>
                <p>Zkontrolujte popřípadně spam složku.</p>
                <p class="text-subtitle-2 pt-4">Nepřišel Vám email? Zkuste ho znovu odeslat.</p>
                <v-form @submit.prevent="verifyEmail" class="pt-4">
                    <v-btn type="submit" color="primary" :disabled="disabledBtn" rounded>
                        Odeslat znova
                    </v-btn>
                </v-form>
                <p class="text-subtitle-2 text-center pt-4 text-green" v-if="message">{{ $t(message) }}</p>
            </v-sheet>
        </v-container>
    </component>
</template>

<style scoped lang="scss">

</style>
