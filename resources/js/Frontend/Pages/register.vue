<template>
    <Layout>
        <div class="justify-center align-center menu w-100">
            <v-sheet :width="900" :class="{'w-100': $vuetify.display.mdAndDown, 'h-100': $vuetify.display.smAndDown}"
                     :elevation="$vuetify.display.smAndDown ? '' : 15">
                <div class="d-flex"
                     :class="{'justify-center': $vuetify.display.mdAndDown, 'align-center h-100': $vuetify.display.smAndDown}">
                    <v-sheet class="flex-column justify-center align-center w-30 author pa-4"
                             :class="{'d-none': $vuetify.display.mdAndDown}">
                        <div v-if="tab===1">
                            <div class="text-h4 pa-4 font-weight-thin text-center">
                                {{ $t('authentication.welcome.back') }}
                            </div>
                            <v-divider class="border-opacity-50 pa-2" color="white"></v-divider>
                        </div>
                        <h2>LearnApp</h2>
                        <p>{{ $t('authentication.welcome.created') }}<br> Jiří Navrátil</p>
                    </v-sheet>
                    <v-sheet class="authentication" :class="{'w-90 pa-8 rounded-lg': $vuetify.display.smAndDown}"
                             :elevation="$vuetify.display.smAndDown ? 4: 0">
                        <v-tabs
                            v-model="tab"
                            align-tabs="center"
                        >
                            <v-tab>
                                {{ $t('authentication.register.register') }}
                            </v-tab>
                            <v-tab>
                                {{ $t('authentication.login') }}
                            </v-tab>
                            <v-tab v-if="tab === 2">
                                Reset hesla
                            </v-tab>
                        </v-tabs>
                        <v-window v-model="tab">
                            <v-window-item :key="1">
                                <RegisterForm/>
                            </v-window-item>
                            <v-window-item :key="2">
                                <LoginForm/>
                            </v-window-item>
                            <v-window-item :key="3">
                                <ResetPassword/>
                            </v-window-item>
                        </v-window>
                    </v-sheet>
                </div>
            </v-sheet>
        </div>
    </Layout>
</template>

<script setup>
import {defineAsyncComponent, ref, watch} from "vue";
import Layout from '../layouts/AuthLayout.vue'
import RegisterForm from "../Components/Authentication/RegisterForm.vue"
import LoginForm from "../Components/Authentication/LoginForm.vue"
import {useRouter} from "vue-router";

const router = useRouter()
const ResetPassword = defineAsyncComponent(() => import("@/Frontend/Components/Authentication/ResetPassword.vue"));
//Register x login
const props = defineProps(['value']);
const tab = ref(props.value);

watch((tab), (val) => {
    switch (val) {
        case 0: {
            router.replace("/register")
            break;
        }
        case 1: {
            router.replace('/login')
            break;
        }
        case 2: {
            router.replace('/passwordreset')
            break;
        }
    }
})
</script>

<style lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;

.w-30 {
    width: 30% !important;
}

.w-90 {
    width: 90% !important;
}

.pa-0 {
    padding: 0 !important;
}

.menu {
    display: flex;
    min-height: 100vh;

    .author {
        display: flex;
        background: #4398f0;
        color: white;
    }

    .authentication {
        box-sizing: border-box;
        width: 75%;
        padding: 16px;
    }
}

@media #{map-get($display-breakpoints, 'md-and-down')} {
    .v-sheet:first-child {
        background: unset !important;
    }
}

@media #{map-get($display-breakpoints, 'sm-and-down')} {

}

</style>
