<template>
    <component :is="DashboardLayout">
        {{subjects}}
        <div class="vh-calc">
            <v-main class="pa-5 d-flex flex-wrap" :class="{'justify-center': $vuetify.display.smAndDown}">
                <v-card
                    v-for="subject in subjects" :key="subject.id"
                    class="pa-2 d-flex flex-column elevation-20"
                    max-width="344"
                >
                    <v-card-text>
                        <p class="text-h4 font-weight-bold text--primary py-4">
                            {{ subject.name }}
                        </p>
                        <div class="text--primary">
                            Žádost o sdílení od uživalele:
                            <div class="font-weight-bold">{{ subject.users.email }}</div>
                            <br>
                        </div>
                        <div class=" font-weight-bold" v-if="subject.permission.permission_id == 1">
                            S právem ke čtení<br>
                        </div>
                        <div class=" font-weight-bold" v-if="subject.permission.permission_id == 2">
                            S právem ke čtení a úpravě<br>
                        </div>
                        <div class="  font-weight-bold" v-if="subject.permission.permission_id == 3">
                            S právem plnou kontrolou<br>
                        </div>
                    </v-card-text>
                    <v-card-actions class="flex-wrap justify-end align-center gp-em-05">
                        <v-btn
                            variant="flat"
                            color="red"
                            :disabled="btnDisabled"
                            @click="shareDelete(subject.slug)"
                        >
                            zrušit
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="green"
                            :disabled="btnDisabled"
                            @click="shareAccept(subject.slug)"
                        >
                            Příjmout
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-main>
        </div>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link, useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";

const props = defineProps({subjects: Object})
const form = useForm();
const btnDisabled = ref(false);
const disabledbtn = () => {
    btnDisabled.value = true;
}

const shareDelete = (slug) => {
    disabledbtn();
    form.delete(route('share.delete', slug), {
        onSuccess: () => {
            btnDisabled.value = false;
        }
    });
}

const shareAccept = (slug) => {
    disabledbtn();
    const form2 = useForm({
        slug: slug,
    })
    form2.post(route('share.accept'), {
        onSuccess: () => {
            btnDisabled.value = false;
        }
    });
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
