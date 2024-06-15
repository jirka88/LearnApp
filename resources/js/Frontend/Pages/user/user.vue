<template>
    <AdminLayout>
        <v-container>
            <Breadcrumbs :items="[{title: 'Uživatelský profil', disabled: true }]"></Breadcrumbs>
            <div class="mt-10 header-info d-flex justify-center pb-4"
                 :class="{'flex-column ga-4': $vuetify.display.smAndDown}, {'ga-12': $vuetify.display.mdAndUp}">
                <div class="avatar" @click="showChangeAvatar">
                    <v-avatar class="avatar" :class="{'margin-center': $vuetify.display.smAndDown}" size="180">
                        <v-img
                            height="100%"
                            cover
                            :src="usr.image ? '/storage/' + usr.image : undefinedProfilePicture"
                            @mouseenter="isHover = true"
                            @mouseleave="isHover = false"
                        />
                    </v-avatar>
                    <v-icon @mouseover="isHovered = true"
                            @mouseleave="isHovered = false" v-show="isHover" icon="mdi-camera"
                            class="position-absolute"/>
                </div>
                <div class="info d-flex justify-center flex-column"
                     :class="{'align-center': $vuetify.display.smAndDown}">
                    <div class="text-h4">{{ usr.firstname + " " + usr.lastname }}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ usr.account_types.type }} účet</div>
                </div>
            </div>
            <h3 class="font-weight-bold text-red text-center pb-4" v-if="!usr.user_active">Účet není aktivní!</h3>
            <v-tabs
                v-model="tab"
                align-tabs="center"
            >
                <v-tab value="1">{{ $t('userAccount.information_user') }}</v-tab>
                <v-tab value="2" v-if="usr.user_active">{{ $t('userAccount.password_reset') }}</v-tab>
                <v-tab v-if="$page.props.user.role.id !== 1" value="3">{{ $t('userAccount.share') }}</v-tab>
            </v-tabs>
            <div class="d-flex justify-center">
                <v-window v-model="tab">
                    <v-window-item value="1">
                        <UpdateUser :usr="usr" :roles="roles" :accountTypes="accountTypes" :licences="licences"/>
                    </v-window-item>
                    <v-window-item v-if="usr.user_active" value="2">
                        <ResetPassword :usr="usr" :errors="errors"/>
                    </v-window-item>
                    <v-window-item v-if="$page.props.user.role.id !== 1" value="3">
                        <share-options :usr="usr" :errors="errors"/>
                    </v-window-item>
                </v-window>
            </div>
            <UploadImage
                v-if="isActive"
                v-model:active="isActive"
            />
        </v-container>
    </AdminLayout>
</template>
<script>
</script>
<script setup>
import AdminLayout from "../../layouts/DashboardLayout.vue";
import UpdateUser from "../../Components/UpdateUser.vue";
import ResetPassword from "../../Components/ResetPassword.vue";
import {defineAsyncComponent, ref, watch} from "vue";
import ShareOptions from "@/Frontend/Components/shareOptions.vue";
import Breadcrumbs from "../../Components/UI/Breadcrumbs.vue";

const UploadImage = defineAsyncComponent(() =>
    import ("@/Frontend/Components/UploadImage.vue")
)
import undefinedProfilePicture from './../../../../assets/user/Default_pfp.svg';

const tab = ref(null);
const isActive = ref(false);
const isHover = ref(false);
defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, 'licences': Array, errors: Object});
const showChangeAvatar = () => {
    isActive.value = true;
}
</script>
<style scoped lang="scss">

.header-info {
    .avatar {
        display: flex;
        justify-content: center;
        position: relative;

        .v-img {
            &:hover {
                cursor: pointer;
                transition: 0.3s ease-in-out !important;
                filter: brightness(50%);
            }
        }

        .v-icon {
            z-index: 200;
            color: white;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
    }
}

.on-hover {
    cursor: pointer;
}

.v-window {
    padding: 2em;
    width: 600px !important;
}

fieldset {
    border-radius: 1em;
}

:deep(.v-messages__message) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
