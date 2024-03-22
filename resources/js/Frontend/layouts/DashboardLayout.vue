<template>
    <Base>
        <v-layout class="flex-column">
            <v-navigation-drawer
                v-model="drawer"
                prominent
                location="left"
            >
                <div class="usr">
                    <Link :href="route('user.info')" class="text-decoration-none text-black">
                        <v-list-item
                            :prepend-avatar="$page.props.user.image ? '/storage/' + $page.props.user.image : undefinedProfilePicture"
                            :title="$page.props.user.firstname"
                            nav
                            height="64"
                            class=" d-flex align-center ga-2"
                        >
                            <div class="text-subtitle-2">{{ $page.props.user.email }}</div>
                        </v-list-item>
                    </Link>
                </div>
                <v-divider></v-divider>
                <v-list density="compact" nav>
                    <Link :href="route('dashboard')">
                        <v-list-item prepend-icon="mdi-home-city" :title="$t('dashboard.home')"
                                     :value="$t('dashboard.home')" active-class="bg-primary"></v-list-item>
                    </Link>
                    <Link v-if="$page.props.permission.view" :href="route('admin')">
                        <v-list-item prepend-icon="mdi-account-cog" :title="$t('dashboard.all_users')"
                                     :value="$t('dashboard.all_users')"></v-list-item>
                    </Link>
                    <v-list-group
                        id="group"
                        value="true"
                        @click="changeStatusSection">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                prepend-icon="mdi-account-circle"
                                :title="$page.props.user.typeAccount == 'Osobní' ? $t('dashboard.section') : $t('dashboard.subjects')"
                            >
                            </v-list-item>
                        </template>
                        <Link :href="route('subject.index')">
                            <v-list-item
                                class="subItem"
                                prepend-icon="mdi-folder-edit"
                                :title="$t('dashboard.organization')">
                            </v-list-item>
                        </Link>
                        <Link v-for="subject in $page.props.user.subjects" :key="subject.id"
                              :href="route('subject.show', subject.slug)">
                            <v-list-item
                                v-if="subject.permission.accepted != 0"
                                class="subItem"
                                :prepend-icon="subject.icon"
                                :title="subject.name">
                            </v-list-item>
                        </Link>
                    </v-list-group>
                    <Link :href="route('share.show')"
                          v-if="$page.props.user.sharedSubjects > 0"
                    >
                        <v-list-item prepend-icon="mdi-share" title="Zobrazit sdílení"
                                     value="zobrazit sdílení">
                        </v-list-item>
                    </Link>
                    <Link :href="route('share.view')"
                          v-if="$page.props.user.subjects.some(subject => subject.permission.accepted == 0)">
                        <v-list-item prepend-icon="mdi-share" title="Povolit sdílení"
                                     value="Povolit sdílení">
                            <template v-slot:append>
                                <v-badge
                                    color="info"
                                    :content="$page.props.user.subjects.filter(item => item.permission.accepted == 0).length"
                                    inline
                                ></v-badge>
                            </template>
                        </v-list-item>
                    </Link>
                    <Link :href="route('user.info')">
                        <v-list-item prepend-icon="mdi-account-cog" :title="$t('dashboard.set_profile')"
                                     :value="$t('dashboard.set_profile')"></v-list-item>
                    </Link>
                    <Link :href="route('user.report')">
                        <v-list-item prepend-icon="mdi-alert" title="Nahlásit chybu"
                                     value="Nahlásit chybu"></v-list-item>
                    </Link>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar>
                <v-btn v-if="$vuetify.display.lgAndUp" :icon="!drawer ? 'mdi-chevron-left' : 'mdi-chevron-right'"
                       @click.stop="drawer = !drawer"></v-btn>
                <v-btn v-if="$vuetify.display.mdAndDown" icon="mdi-format-list-bulleted"
                       @click.stop="drawer = !drawer"></v-btn>
                <v-spacer></v-spacer>
                <v-select
                    v-model="select"
                    @update:modelValue="changeLanguage"
                    :items="languages"
                    :item-props="itemProps"
                    variant="outlined"
                    hide-details
                    return-object
                >
                    <template v-slot:selection="{ item}">
                        <div class="d-flex justify-content-center align-items-center ga-2">
                            <v-img width="2em" max-height="2em" :src="item.raw.image"/>
                            {{ item.title }}
                        </div>
                    </template>
                    <template v-slot:item="{ item, props }">
                        <v-list-item v-bind="props">
                            <template #title>
                                <div class="d-flex justify-content-center align-items-center ga-2">
                                    <v-img max-width="2em" max-height="2em" :src="item.raw.image"/>
                                    {{ item.title }}
                                </div>
                            </template>
                        </v-list-item>
                    </template>
                </v-select>
                <Link :href="route('logout')">
                    <v-btn icon>
                        <v-icon>mdi-export</v-icon>
                    </v-btn>
                </Link>
            </v-app-bar>
            <v-main class="vh-calc">
                <slot>
                </slot>
            </v-main>
            <v-footer class="pa-0 primary-bg">
                <p class="text-center pa-4 w-100 text-white" :class="drawer ? 'move' : 'move-back'">
                    {{ $t('authentication.welcome.created') }} Jiří
                    Navrátil - {{ new Date().getFullYear() }}</p>
            </v-footer>
        </v-layout>
    </Base>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import {loadLanguageAsync} from 'laravel-vue-i18n';
import Base from "./../Pages/Base.vue"
import {Inertia} from "@inertiajs/inertia";
import undefinedProfilePicture from './../../../assets/user/Default_pfp.svg';
import czechFlag from "./../../../assets/ui/flags/czech.svg"
import britishFlag from "./../../../assets/ui/flags/united_kingdom.svg"

const drawer = ref(true);

const select = ref({language: localStorage.getItem('langTitle') || 'Česky', ISO: localStorage.getItem('lang') || 'cs', 'image': localStorage.getItem('langImage') || czechFlag});

const languages = [
    {language: 'Česky', ISO: 'cs', image: czechFlag}, {
        language: 'English',
        ISO: 'en',
        image: britishFlag
    }]

const changeLanguage = () => {
    Inertia.post(route('language', {'language': select.value.ISO}));
    localStorage.setItem('langTitle', select.value.language);
    localStorage.setItem('lang', select.value.ISO);
    localStorage.setItem('langImage', select.value.image)
    loadLanguageAsync(select.value.ISO);
}
const getIfIsOpenSections = () => {
    const status = localStorage.getItem("layoutOpen");
    if(status) {
        return status;
    }
    else {
        localStorage.setItem('layoutOpen', false);
    }
}
const changeStatusSection = () => {
    const status = localStorage.getItem("layoutOpen");
    console.log(status);
    if(!status) {
        localStorage.setItem('layoutOpen', false);
    }
    else {
        localStorage.setItem('layoutOpen', true);
    }

}
const itemProps = (item) =>{
    return {title: item.language,
        ISO: item.ISO,
        image: item.image}
}
</script>

<style lang="scss">
.usr {
    .v-list-item-title {
        font-size: 1.2em !important;
    }
}

.v-app-bar {
    gap: 6em;

    .v-icon {
        color: black !important;
    }

    .v-select {
        max-width: 10em;
    }
}

.v-list-item {
    padding: 0.7em !important;
}

#group {
    .subItem:first-child {
        padding-inline-start: 8px !important;
    }

    .subItem {
        padding-inline-start: 16px !important;
    }
}

.v-list-item--active {
    background: gray;
}

.v-footer {
    .move {
        transition: .8s;
        margin-left: 255px;
    }

    .move-back {
        transition: .8s;
    }
}
</style>
