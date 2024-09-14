<template>
    <v-sheet class="pa-8" rounded="xl" elevation="2">
        <div class="avatar pb-6 d-flex flex-column ga-4" @click="showChangeAvatar">
            <p>Fotografie</p>
            <v-avatar
                class="avatar"
                :class="{ 'margin-center': $vuetify.display.smAndDown }"
                size="120"
            >
                <v-img
                    height="100%"
                    cover
                    :src="usr.image ? '/storage/' + usr.image : undefinedProfilePicture"
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
        <UploadImage v-if="isActive" v-model:active="isActive" />
        <v-form
            :disabled="!usr.user_active"
            @submit.prevent="form.put(route('user.update', { id: usr.id }))"
        >
            <div class="text-subtitle-1 text-medium-emphasis">
                {{ $t('global.name') }}:
            </div>
            <v-text-field
                v-model="form.firstname"
                :rules="[rules.required, rules.firstNameLength]"
                variant="outlined"
            ></v-text-field>

            <div class="text-subtitle-1 text-medium-emphasis">
                {{ $t('global.surname') }}:
            </div>

            <v-text-field
                v-model="form.lastname"
                :rules="[rules.required, rules.lastNameLength]"
                variant="outlined"
            ></v-text-field>

            <div class="text-subtitle-1 text-medium-emphasis">Email:</div>

            <v-text-field
                v-model="form.email"
                :rules="[rules.email, rules.required]"
                :disabled="!$page.props.permission.view || !usr.user_active"
                variant="outlined"
            ></v-text-field>
            <div class="text-subtitle-1 text-medium-emphasis">Typ účtu:</div>

            <v-select
                v-model="form.type"
                :items="accountTypes"
                item-title="type"
                item-value="id"
                label="Select"
                persistent-hint
                return-object
                single-line
                variant="outlined"
            ></v-select>
            <BtnSetting> </BtnSetting>
        </v-form>
    </v-sheet>
</template>
<script setup>
import rules from './../../../rules/rules'
import { useForm } from '@inertiajs/inertia-vue3'
import { defineAsyncComponent, ref } from 'vue'
import undefinedProfilePicture from './../../../../../assets/user/Default_pfp.svg'
import BtnSetting from '@/Frontend/Components/BtnSetting.vue'
const UploadImage = defineAsyncComponent(
    () => import('@/Frontend/Components/UploadImage.vue')
)

const isActive = ref(false)
const isHover = ref(false)
const props = defineProps({
    usr: Object,
    accountTypes: Array
})
const form = useForm({
    firstname: props.usr.firstname,
    lastname: props.usr.lastname,
    email: props.usr.email,
    type: {
        type: props.usr.account_types.type,
        id: props.usr.account_types.id
    }
})

const showChangeAvatar = () => {
    isActive.value = true
}
</script>
<style scoped lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;
.avatar {
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
</style>
