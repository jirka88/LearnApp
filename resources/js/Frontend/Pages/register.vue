<template>
    <Layout>
        <div class="justify-center align-center menu w-100" >
            <v-sheet :width="900" :class="{'w-100': $vuetify.display.mdAndDown}" :elevation="15">
                <div class="d-flex" :class="{'justify-center': $vuetify.display.mdAndDown}">
                    <v-sheet class="flex-column justify-center align-center w-25 author pa-4" :class="{'d-none': $vuetify.display.mdAndDown}" >
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
                                <v-form>
                                <v-container class="d-flex flex-column pa-2 w-75" :class="{'w-100': $vuetify.display.mdAndDown}">
                                    <h1 class="pa-5 text-center">Registrace</h1>
                                            <v-text-field
                                                v-model="firstnameR"
                                                prepend-inner-icon="mdi-account"
                                                :rules="nameRules"
                                                variant="outlined"
                                                :counter="15"
                                                label="Jméno"
                                                required />
                                            <v-text-field
                                                v-model="emailR"
                                                :rules="emailRules"
                                                prepend-inner-icon="mdi-email"
                                                variant="outlined"
                                                label="E-mail"
                                                required
                                            ></v-text-field>
                                            <v-text-field
                                                v-model="passwordR"
                                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                                :type="show ? 'text' : 'password'"
                                                prepend-inner-icon="mdi-lock"
                                                variant="outlined"
                                                name="input-10-1"
                                                label="Heslo"
                                                hint="At least 8 characters"
                                                counter
                                                @click:append="show = !show"
                                            ></v-text-field>
                                            <v-text-field
                                                v-model="passwordRc"
                                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                                :type="show1 ? 'text' : 'password'"
                                                prepend-inner-icon="mdi-lock"
                                                variant="outlined"
                                                name="input-10-1"
                                                label="Potvrzení hesla"
                                                hint="At least 8 characters"
                                                counter
                                                @click:append="show1 = !show1"
                                            ></v-text-field>
                                            <v-btn
                                                type="submit"
                                                color="blue"
                                                class="mt-2"
                                            >
                                                Registrovat!
                                            </v-btn>
                                </v-container>
                            </v-form>
                            </v-window-item>
                            <v-window-item :key="2">
                                <v-form>
                                    <v-container class="d-flex flex-column pa-2 w-75">
                                        <h1 class="pa-5 text-center">Přihlášení</h1>
                                        <v-text-field
                                            v-model="firstname"
                                            :rules="nameRules"
                                            :counter="10"
                                            label="First name"
                                            required />
                                        <v-text-field
                                            v-model="email"
                                            :rules="emailRules"
                                            label="E-mail"
                                            required
                                        ></v-text-field>
                                    </v-container>
                                </v-form>
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
import {ref} from "vue";
//Register x login
const props = defineProps(['value']);
//register
const tab = ref(props.value);
const show = ref('');
const show1 = ref('');

const firstnameR = ref('');
const emailR = ref('');
const passwordR = ref('');
const passwordRc = ref('');

//login



</script>

<style lang="scss">
    .menu {
        height: 100vh;
        display: flex;
            .author {
                display: flex;
                background: blue;
                color: white;
                .authentication {
                    box-sizing: border-box;
            }
        }
    }

</style>
