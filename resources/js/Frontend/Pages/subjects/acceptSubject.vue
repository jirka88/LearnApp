<template>
    <component :is="DashboardLayout">
        <div class="vh-calc">
            <v-main class="pa-5 d-flex flex-wrap" :class="{'justify-center': $vuetify.display.smAndDown}">
                <v-card
                    v-for="subject in subjects.patritions" :key="subject.id"
                    class="pa-2 d-flex flex-column elevation-20"
                    max-width="344"
                >
                    <v-card-text>
                        <p class="text-h4 font-weight-bold text--primary py-4">
                            {{subject.name}}
                        </p>
                        <div class="text--primary">
                            Žádost o sdílení od uživalele: <div class="font-weight-bold">{{subject.users[0].email}}</div><br>
                        </div>
                        <div class="text--primary font-weight-bold" v-if="subject.pivot.permission_id == 1">
                            S právem ke čtení<br>
                        </div>
                        <div class="text--primary  font-weight-bold" v-if="subject.pivot.permission_id == 2">
                            S právem ke čtení a úpravě<br>
                        </div>
                        <div class="text--primary  font-weight-bold" v-if="subject.pivot.permission_id == 3">
                            S právem plnou kontrolou<br>
                        </div>
                    </v-card-text>
                    <v-card-actions class="flex-wrap justify-end align-center gp-em-05">
                        <v-btn
                            variant="flat"
                            color="red"
                            @click="shareDelete(subject.slug)"
                        >
                        zrušit
                        </v-btn>
                        <Link>
                            <v-btn
                                variant="flat"
                                color="green"
                            >
                            Příjmout</v-btn>
                        </Link>
                    </v-card-actions>
                </v-card>
            </v-main>
            {{subjects}}
        </div>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link, useForm} from "@inertiajs/inertia-vue3";
const props = defineProps({subjects: Object})
const form = useForm();

const shareDelete = (slug) =>{
    form.delete(route('share.delete',slug))
}
</script>

<style scoped lang="scss">
.v-main {
    gap: 1em;
    .v-card {
        flex: 1 1 auto;
        width: 300px !important;
        min-height: 14em !important;
        .text-h4 {
            text-wrap: balance;
        }
    }
}
</style>
