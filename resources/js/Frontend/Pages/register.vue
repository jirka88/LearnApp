<template>
    <Layout>
        <div class="justify-center align-center menu w-100">
            <v-sheet :width="900" :class="{'w-100': $vuetify.display.mdAndDown}" :elevation="$vuetify.display.smAndDown ? '' : 15">
                <div class="d-flex" :class="{'justify-center': $vuetify.display.mdAndDown}">
                    <v-sheet class="flex-column justify-center align-center w-30 author pa-4"
                             :class="{'d-none': $vuetify.display.mdAndDown}">

                        <div class="text-h4 pa-4 font-weight-thin" v-if="tab===1">
                            Vítejte zpět
                        </div>
                        <h2>LearnApp</h2>
                        <p>Created by:<br> Jiří Navrátil</p>
                    </v-sheet>
                    <v-sheet class="w-75 authentication pa-4">
                        <v-tabs
                            v-model="tab"
                            align-tabs="center"
                        >
                            <v-tab>
                                Registrace
                            </v-tab>
                            <v-tab>
                                Přihlášení
                            </v-tab>
                        </v-tabs>
                        <v-window v-model="tab">
                            <v-window-item :key="1">
                                <RegisterForm/>
                            </v-window-item>
                            <v-window-item :key="2">
                                <LoginForm/>
                            </v-window-item>
                        </v-window>
                    </v-sheet>
                </div>
            </v-sheet>
        </div>
    </Layout>
</template>

<script setup>
import Layout from './../layouts/Layout.vue'
import RegisterForm from "./../Components/RegisterForm.vue"
import LoginForm from "./../Components/LoginForm.vue"
import {ref} from "vue";
//Register x login
const props = defineProps(['value']);
const tab = ref(props.value);

</script>

<style lang="scss">
.w-30 {
    width: 30% !important;
}
.menu {
    height: 100vh;
    display: flex;
    .author {
        display: flex;
        background: #4398f0;
        color: white;
        .authentication {
            box-sizing: border-box;
        }
    }
}

</style>
