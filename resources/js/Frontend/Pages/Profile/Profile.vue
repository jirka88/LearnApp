<template>
    <AdminLayout>
        <v-container>
            <Breadcrumbs
                :items="[{ title: 'Uživatelský profil', disabled: true }]"
            ></Breadcrumbs>
            <div class="pt-8 setting">
                <InformationAboutSetting
                    title="Základní profil"
                    text="Zde si můžete nastavit základní uživatelské informace"
                ></InformationAboutSetting>
                <UserBasicSettings :usr="usr" :accountTypes="accountTypes">
                </UserBasicSettings>
            </div>
            <div class="pt-8 setting">
                <InformationAboutSetting
                    title="Uživatelská role"
                ></InformationAboutSetting>
                <UserRoleSetting :usr="usr" :roles="roles"> </UserRoleSetting>
            </div>
            <div class="pt-8 setting">
                <InformationAboutSetting title="Licence"></InformationAboutSetting>
                <UserLicenceSetting :usr="usr" :licences="licences"></UserLicenceSetting>
            </div>
            <div class="pt-8 setting" v-if="$page.props.user.id === usr.id">
                <InformationAboutSetting
                    title="Resetování hesla"
                ></InformationAboutSetting>
                <UserResetPasswordSetting :usr="usr"></UserResetPasswordSetting>
            </div>
            <div class="pt-8 setting">
                <InformationAboutSetting
                    :title="$t('userAccount.share')"
                    :text="$t('userAccount.shareInfo')"
                ></InformationAboutSetting>
                <UserShareSetting :usr="usr"></UserShareSetting>
            </div>
            {{ $page.props.user }}
            <!--<div
                class="mt-10 header-info d-flex justify-center pb-4"
                :class="
                    ({ 'flex-column ga-4': $vuetify.display.smAndDown },
                    { 'ga-12': $vuetify.display.mdAndUp })
                "
            >
                <div class="avatar" @click="showChangeAvatar">
                    <v-avatar
                        class="avatar"
                        :class="{ 'margin-center': $vuetify.display.smAndDown }"
                        size="180"
                    >
                        <v-img
                            height="100%"
                            cover
                            :src="
                                usr.image
                                    ? '/storage/' + usr.image
                                    : undefinedProfilePicture
                            "
                            @mouseenter="isHover = true"
                            @mouseleave="isHover = false"
                        />
                    </v-avatar>
                    <v-icon
                        @mouseover="isHovered = true"
                        @mouseleave="isHovered = false"
                        v-show="isHover"
                        icon="mdi-camera"
                        class="position-absolute"
                    />
                </div>
                <div
                    class="info d-flex justify-center flex-column"
                    :class="{ 'align-center': $vuetify.display.smAndDown }"
                >
                    <div class="text-h4">
                        {{ usr.firstname + ' ' + usr.lastname }}
                    </div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ usr.account_types.type }} účet</div>
                </div>
            </div>
            <h3
                class="font-weight-bold text-red text-center pb-4"
                v-if="!usr.user_active"
            >
                Účet není aktivní!
            </h3>
            <v-tabs v-model="tab" align-tabs="center">
                <v-tab value="1">{{ $t('userAccount.information_user') }}</v-tab>
                <v-tab value="2" v-if="usr.user_active">{{
                    $t('userAccount.password_reset')
                }}</v-tab>
                <v-tab v-if="$page.props.user.role.id !== 1" value="3">{{
                    $t('userAccount.share')
                }}</v-tab>
            </v-tabs>
            <div class="d-flex justify-center">
                <v-window v-model="tab">
                    <v-window-item value="1">
                        <UpdateUser
                            :usr="usr"
                            :roles="roles"
                            :accountTypes="accountTypes"
                            :licences="licences"
                        />
                    </v-window-item>
                    <v-window-item v-if="usr.user_active" value="2">
                        <ResetPassword :usr="usr" :errors="errors" />
                    </v-window-item>
                    <v-window-item v-if="$page.props.user.role.id !== 1" value="3">
                        <ShareOptions :usr="usr" :errors="errors" />
                    </v-window-item>
                </v-window>
            </div>
            <UploadImage v-if="isActive" v-model:active="isActive" />-->
        </v-container>
    </AdminLayout>
</template>
<script></script>
<script setup>
import AdminLayout from '../../layouts/DashboardLayout.vue'
import { defineAsyncComponent, ref } from 'vue'
import Breadcrumbs from '../../Components/UI/Breadcrumbs.vue'
import UserBasicSettings from '@/Frontend/Pages/Profile/Partials/UserBasicSettings.vue'
import InformationAboutSetting from '@/Frontend/Pages/Profile/Partials/InformationAboutSetting.vue'
import undefinedProfilePicture from './../../../../assets/user/Default_pfp.svg'
import UserRoleSetting from '@/Frontend/Pages/Profile/Partials/UserRoleSetting.vue'
import UserLicenceSetting from '@/Frontend/Pages/Profile/Partials/UserLicenceSetting.vue'
import UserShareSetting from '@/Frontend/Pages/Profile/Partials/UserShareSetting.vue'
import UserResetPasswordSetting from '@/Frontend/Pages/Profile/Partials/UserResetPasswordSetting.vue'

const UploadImage = defineAsyncComponent(
    () => import('@/Frontend/Components/UploadImage.vue')
)

const tab = ref(null)
const isActive = ref(false)
const isHover = ref(false)
defineProps({
    usr: Object,
    roles: Array,
    accountTypes: Array,
    licences: Array,
    errors: Object
})
const showChangeAvatar = () => {
    isActive.value = true
}
</script>
<style lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;
.setting {
    display: grid;
    grid-template-columns: 0.3fr 0.7fr;
    column-gap: 4em;
    @media #{map-get($display-breakpoints, 'md-and-down')} {
        grid-template-columns: 1fr;
        gap: 2em;
    }
    .v-text-field {
        width: min(100%, 40em);
        @media #{map-get($display-breakpoints, 'md-and-down')} {
            width: 100%;
        }
    }
}
</style>
