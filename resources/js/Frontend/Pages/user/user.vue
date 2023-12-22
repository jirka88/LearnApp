<template>
    <AdminLayout>
        <v-container>
            <Breadcrumbs :items="[{title: 'Uživatelský profil', disabled: true }]"></Breadcrumbs>
            <div class="mt-10 d-grid align-center justify-center"
                 :class="{'gp-4 mobile-variant': $vuetify.display.smAndDown}">

                <div class="avatar" @click="showChangeAvatar">
                    <v-avatar class="avatar position-relative" :class="{'margin-center': $vuetify.display.smAndDown}" size="180">
                        <v-img
                            height="100%"
                            cover
                            src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
                        />
                        <v-icon icon="mdi-camera" class="position-absolute" />
                    </v-avatar>
                </div>
                <div class="info d-flex justify-center flex-column"
                     :class="{'align-center': $vuetify.display.smAndDown}">
                    <div class="text-h4">{{ usr.firstname + " " + usr.lastname}}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ usr.account_types.type }} účet</div>
                </div>
                <v-tabs
                    v-model="tab"
                    align-tabs="center"
                >
                    <v-tab value="1">{{$t('userAccount.information_user')}}</v-tab>
                    <v-tab value="2">{{$t('userAccount.password_reset')}}</v-tab>
                    <v-tab v-if="$page.props.user.role.id !== 1" value="3">Sdílení</v-tab>
                </v-tabs>
                <v-window v-model="tab" :class="{'width-vw-85': $vuetify.display.smAndDown,
                                                'width-vw-100': $vuetify.display.xs }">
                    <v-window-item value="1">
                        <UpdateUser :usr="usr" :roles="roles" :accountTypes="accountTypes" :licences="licences"/>
                    </v-window-item>
                    <v-window-item value="2">
                        <ResetPassword :usr="usr" :errors="errors"/>
                    </v-window-item>
                    <v-window-item v-if="$page.props.user.role.id !== 1" value="3">
                        <share-options :usr="usr" :errors="errors"/>
                    </v-window-item>
                </v-window>
            </div>
            <UploadImage v-model="isActive" :isActive="isActive"  @close="isActive = false"/>
        </v-container>
    </AdminLayout>
</template>
<script>
</script>
<script setup>
import AdminLayout from "../../layouts/DashboardLayout.vue";
import UpdateUser from "../../Components/UpdateUser.vue";
import ResetPassword from "../../Components/ResetPassword.vue";
import {defineAsyncComponent, ref} from "vue";
import ShareOptions from "@/Frontend/Components/shareOptions.vue";
import Breadcrumbs from "../../Components/UI/Breadcrumbs.vue";
const UploadImage = defineAsyncComponent(() =>
    import ("@/Frontend/Components/UploadImage.vue")
)

const tab = ref(null);
const isActive = ref(false);
defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, 'licences': Array, errors: Object});

const showChangeAvatar = () => {
    isActive.value = true;
}
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
        &:hover {
            cursor: pointer;
            transition: 0.3s;
            filter: brightness(80%);
        }
        &:hover .v-icon {
            display: block;
        }
        .v-icon {
            z-index: 200;
            color: white;
            top: 50%;
            display: none;
            left: 50%;
            transform: translate(-50%,-50%);
        }
    }
    .on-hover {
        cursor: pointer;
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
    grid-template-areas: 'avatar'
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
