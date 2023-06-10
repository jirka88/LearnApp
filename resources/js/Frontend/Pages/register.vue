<template>
    <Layout>
        <div class="align-center justify-center menu w-100">
        <v-sheet :width="800" :elevation="15">
            <div class="d-flex">
                <v-sheet class="justify-center align-center w-25 author" :class="{'d-none': $vuetify.display.mdAndDown}" >
                    <p>Vytvořeno</p>
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
                            <v-container class="d-flex flex-column pa-2 w-75">
                                <h1>Register</h1>
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
                        <v-window-item :key="2">
                            <v-form>
                                <v-container class="d-flex flex-column  w-75">
                                    <h1 class="pa-8 text-center">Příhlášení</h1>
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

const tab = ref('');
</script>

<style lang="scss">
    .menu {
        height: 100vh;
        display: grid;
            .author {
                display: flex;
                background: blue;
                .authentication {
                    box-sizing: border-box;
                    .v-input {
                        width: 8em !important;
                    }
            }
        }
    }

</style>
