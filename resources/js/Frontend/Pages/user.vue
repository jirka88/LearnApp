<template>
    <AdminLayout>
        <v-container>
            <div class="mt-10 user-info d-grid align-center justify-center"
                 :class="{'gp-4 mobile-variant': $vuetify.display.smAndDown}">
                <v-avatar class="avatar" :class="{'margin-center': $vuetify.display.smAndDown}" size="180">
                    <v-img
                        height="100%"
                        cover
                        src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
                    />
                </v-avatar>
                <div class="info d-flex justify-center flex-column" :class="{'min-width-vw-65 align-center': $vuetify.display.smAndDown,
                                                                            'min-width-vw-85':$vuetify.display.xs }">
                    <div class="text-h4">{{ usr.firstname }}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ this.$page.props.user.typeAccount }} účet</div>
                </div>

                <v-tabs
                    v-model="tab"
                    align-tabs="center"
                >

                <v-tab value="1">Uživatelské informace</v-tab>
                <v-tab value="2">Resetování hesla</v-tab>
                </v-tabs>
                {{tab}}
                <v-window v-model="tab">
                    <v-window-item value="1">
                        <UpdateUser :usr="usr" :roles="roles" :accountTypes="accountTypes" />
                    </v-window-item>
                    <v-window-item value="2">
                        <ResetPassword :usr="usr" :errors="errors" />
                    </v-window-item>
                </v-window>
            </div>
        </v-container>
    </AdminLayout>
</template>
<script>
</script>
<script setup>
import AdminLayout from "../layouts/DashboardLayout.vue";
import UpdateUser from "./../Components/UpdateUser.vue";
import ResetPassword from "./../Components/ResetPassword.vue";
import {ref} from "vue";
const tab = ref(null);
defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, errors: Object});
</script>
<style scoped lang="scss">
.d-grid {

    .account {
        grid-area: account;
    }

    .avatar {
        grid-area: avatar;
    }

    .info {
        grid-area: info;
        max-width: 35em;
    }

    .password {
        grid-area: password;
        width: 35em;
    }
}
.user-info {
    gap: 4em;
    min-width: 320px;
}
.gp-4 {
    gap: 2em;
}
.mobile-variant {
    grid-auto-rows: 0.1fr 0.1fr 1fr 1fr;
    grid-template-areas: 'avatar'
                        'info'
                            'account'
                            'password' !important;
}
fieldset {
    border-radius: 1em;
    box-shadow: 1em 1em gray;
}

:deep(.v-messages__message) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
