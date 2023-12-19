<template>
    <Base>
        <AdminLayout>
            <div class="menuSubject pa-0">
                <v-container class="pt-0 pb-0">
                    <div class="content d-flex justify-center align-center gp-em-05 w-100"
                         :class="{'flex-column': $vuetify.display.smAndDown}">
                        <v-col cols="auto">
                            <Link :href="route('subject.index')">
                                <v-btn icon="mdi-arrow-left-thick" class="bg-green" size="x-large"></v-btn>
                            </Link>
                        </v-col>
                        <slot>

                        </slot>
                    </div>
                </v-container>
            </div>
        </AdminLayout>
    </Base>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import AdminLayout from "@/Frontend/layouts/DashboardLayout.vue";
import Base from "./../Pages/Base.vue"
</script>
<style scoped lang="scss">

.menuSubject {
    background: url('./../../../assets/authentication/d.svg') no-repeat center;
    background-size: cover;
    height: calc(100vh - 64px);

    .content {
        height: calc(100vh - 64px);
    }
}


</style>
