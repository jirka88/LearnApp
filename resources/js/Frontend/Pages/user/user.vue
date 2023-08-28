<template>
    <AdminLayout>
        <v-container>
            <div class="mt-10 d-grid align-center justify-center"
                 :class="{'gp-4 mobile-variant': $vuetify.display.smAndDown}">
                <div class="avatar">
                    <v-avatar class="avatar" :class="{'margin-center': $vuetify.display.smAndDown}" size="180">
                        <v-img
                            height="100%"
                            cover
                            src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
                        />
                    </v-avatar>
                </div>
                <div class="info d-flex justify-center flex-column"
                     :class="{'align-center': $vuetify.display.smAndDown}">
                    <div class="text-h4">{{ usr.firstname }}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ usr.account_types.type }} účet</div>
                </div>

                <v-tabs
                    v-model="tab"
                    align-tabs="center"
                >
                    <v-tab value="1">Uživatelské informace</v-tab>
                    <v-tab value="2">Resetování hesla</v-tab>
                    <v-tab value="3">Sdílení</v-tab>
                </v-tabs>
                <v-window v-model="tab" :class="{'width-vw-85': $vuetify.display.smAndDown,
                                                'width-vw-100': $vuetify.display.xs }">
                    <v-window-item value="1">
                        <UpdateUser :usr="usr" :roles="roles" :accountTypes="accountTypes"/>
                    </v-window-item>
                    <v-window-item value="2">
                        <ResetPassword :usr="usr" :errors="errors"/>
                    </v-window-item>
                    <v-window-item value="3">
                        <share-options :usr="usr" :errors="errors"/>
                    </v-window-item>

                </v-window>
            </div>
        </v-container>
    </AdminLayout>
</template>
<script>
</script>
<script setup>
import AdminLayout from "../../layouts/DashboardLayout.vue";
import UpdateUser from "../../Components/UpdateUser.vue";
import ResetPassword from "../../Components/ResetPassword.vue";
import {ref} from "vue";
import ShareOptions from "@/Frontend/Components/shareOptions.vue";

const tab = ref(null);
defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, errors: Object});
</script>
<style scoped lang="scss">
.d-grid {
    display: grid;
    grid-template-areas: 'avatar info'
                        'tabs tabs';
    grid-column-gap: 4em;
    grid-row-gap: 2em;
    .avatar {
        grid-area: avatar;
        display: flex;
        justify-content: center;
    }

    .v-tabs {
        grid-area: tabs;
    }

    .info {
        grid-area: info;
    }

    .menus {
        grid-area: menu;
    }

    .v-window {
        padding: 2em;
        grid-column: 1 / span 2;
        width: 600px;
    }
}

.gp-4 {
    gap: 2em;
}

.mobile-variant {
    grid-template-areas:    'avatar'
                        'info'
                        'tabs'
                        'menu' !important;
    .v-window {
        grid-column: unset;
    }
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
