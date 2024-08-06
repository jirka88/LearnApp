<template>
    <Base>
        <v-layout class="flex-column">
            <v-navigation-drawer v-model="drawer" prominent location="left">
                <div class="usr">
                    <Link
                        :href="!$page.props.user.verified ? '' : route('user.info')"
                        class="text-decoration-none text-black"
                    >
                        <v-list-item
                            :prepend-avatar="
                                $page.props.user.image
                                    ? '/storage/' + $page.props.user.image
                                    : undefinedProfilePicture
                            "
                            :title="$page.props.user.firstname"
                            nav
                            height="64"
                            :disabled="!$page.props.user.verified"
                            class="d-flex align-center ga-2"
                        >
                            <div class="text-subtitle-2">
                                {{ $page.props.user.email }}
                            </div>
                        </v-list-item>
                    </Link>
                </div>
                <v-list density="compact" nav flat :disabled="!$page.props.user.verified">
                    <Link :href="route('dashboard')">
                        <v-list-item
                            prepend-icon="mdi-home-city"
                            :class="
                                $page.props.settings.url === 'dashboard' ? 'active' : ''
                            "
                            :title="$t('dashboard.home')"
                            :value="$t('dashboard.home')"
                        ></v-list-item>
                    </Link>
                </v-list>
                <v-list
                    density="compact"
                    nav
                    v-if="$page.props.permission.view"
                    @update:opened="(newOpened) => (openedN = newOpened?.slice(-1))"
                    v-model:opened="opened"
                >
                    <v-list-group id="group" value="groupAdmin">
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props" title="Administrace">
                            </v-list-item>
                        </template>
                        <Link :href="route('adminusers')">
                            <v-list-item
                                prepend-icon="mdi-account-cog"
                                :class="
                                    $page.props.settings.url ===
                                    'dashboard/admin/controll'
                                        ? 'active'
                                        : ''
                                "
                                :title="$t('dashboard.all_users')"
                                :value="$t('dashboard.all_users')"
                            ></v-list-item>
                        </Link>
                        <Link :href="route('adminlog')">
                            <v-list-item
                                prepend-icon="mdi-file-document"
                                :class="
                                    $page.props.settings.url ===
                                    'dashboard/admin/controll/log'
                                        ? 'active'
                                        : ''
                                "
                                title="Logování"
                                value="Logování"
                            >
                            </v-list-item>
                        </Link>
                    </v-list-group>
                    <v-list-group id="group" value="groupSubjects">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                :title="
                                    $page.props.user.typeAccount == 'Osobní'
                                        ? $t('dashboard.section')
                                        : $t('dashboard.subjects')
                                "
                            >
                            </v-list-item>
                        </template>
                        <Link :href="route('subject.index')">
                            <v-list-item
                                class="subItem"
                                :class="
                                    $page.props.settings.url ===
                                    'dashboard/manager/subject'
                                        ? 'active'
                                        : ''
                                "
                                prepend-icon="mdi-folder-edit"
                                :title="$t('dashboard.organization')"
                            >
                            </v-list-item>
                        </Link>
                        <Link
                            v-for="subject in $page.props.user.subjects"
                            :key="subject.id"
                            :href="route('subject.show', subject.slug)"
                        >
                            <v-list-item
                                v-if="subject.permission.accepted != 0"
                                class="subItem"
                                :prepend-icon="subject.icon"
                                :title="subject.name"
                            >
                            </v-list-item>
                        </Link>
                    </v-list-group>
                    <v-list-group
                        id="group"
                        value="groupShare"
                        v-if="
                            $page.props.user.sharedSubjects > 0 ||
                            $page.props.user.subjects.some(
                                (subject) => subject.permission.accepted == 0
                            )
                        "
                    >
                        <template v-slot:activator="{ props }">
                            <v-list-item v-bind="props" title="Sdílení"> </v-list-item>
                        </template>
                        <Link
                            :href="route('share.show')"
                            v-if="$page.props.user.sharedSubjects > 0"
                        >
                            <v-list-item
                                prepend-icon="mdi-share"
                                title="Zobrazit sdílení"
                                value="zobrazit sdílení"
                            >
                            </v-list-item>
                        </Link>
                        <Link
                            :href="route('share.view')"
                            v-if="
                                $page.props.user.subjects.some(
                                    (subject) => subject.permission.accepted == 0
                                )
                            "
                        >
                            <v-list-item
                                prepend-icon="mdi-share"
                                title="Povolit sdílení"
                                value="Povolit sdílení"
                            >
                                <template v-slot:append>
                                    <v-badge
                                        color="info"
                                        :content="
                                            $page.props.user.subjects.filter(
                                                (item) => item.permission.accepted == 0
                                            ).length
                                        "
                                        inline
                                    ></v-badge>
                                </template>
                            </v-list-item>
                        </Link>
                    </v-list-group>
                </v-list>
                <v-list density="compact" nav>
                    <Link :href="route('user.info')">
                        <v-list-item
                            prepend-icon="mdi-account-cog"
                            :class="
                                $page.props.settings.url === 'dashboard/user'
                                    ? 'active'
                                    : ''
                            "
                            :title="$t('dashboard.set_profile')"
                            :value="$t('dashboard.set_profile')"
                        ></v-list-item>
                    </Link>
                    <Link :href="route('user.report')">
                        <v-list-item
                            prepend-icon="mdi-alert"
                            title="Nahlásit chybu"
                            value="Nahlásit chybu"
                        ></v-list-item>
                    </Link>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar>
                <v-btn
                    v-if="$vuetify.display.lgAndUp"
                    :icon="!drawer ? 'mdi-chevron-left' : 'mdi-chevron-right'"
                    @click.stop="drawer = !drawer"
                ></v-btn>
                <v-btn
                    v-if="$vuetify.display.mdAndDown"
                    icon="mdi-format-list-bulleted"
                    @click.stop="drawer = !drawer"
                ></v-btn>
                <v-container class="d-flex ga-4">
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
                        <template v-slot:selection="{ item }">
                            <div
                                class="d-flex justify-content-center align-items-center ga-2"
                            >
                                <v-img
                                    width="2em"
                                    max-height="2em"
                                    :src="item.raw.image"
                                />
                                {{ item.title }}
                            </div>
                        </template>
                        <template v-slot:item="{ item, props }">
                            <v-list-item v-bind="props">
                                <template #title>
                                    <div
                                        class="d-flex justify-content-center align-items-center ga-2"
                                    >
                                        <v-img
                                            max-width="2em"
                                            max-height="2em"
                                            :src="item.raw.image"
                                        />
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
                </v-container>
            </v-app-bar>
            <v-main class="vh-calc">
                <slot></slot>
            </v-main>
            <v-footer class="pa-0 primary-bg">
                <p
                    class="text-center pa-4 w-100 text-white"
                    :class="drawer ? 'move' : 'move-back'"
                >
                    {{ $t('authentication.welcome.created') }} Jiří Navrátil -
                    {{ new Date().getFullYear() }}
                </p>
            </v-footer>
        </v-layout>
    </Base>
</template>
<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import { computed, ref, watch } from 'vue'
import { loadLanguageAsync } from 'laravel-vue-i18n'
import Base from './../Pages/Base.vue'
import { Inertia } from '@inertiajs/inertia'
import undefinedProfilePicture from './../../../assets/user/Default_pfp.svg'
import czechFlag from './../../../assets/ui/flags/czech.svg'
import britishFlag from './../../../assets/ui/flags/united_kingdom.svg'
import prefixGroups from './urlMappings'

const drawer = ref(true)

const openedN = ref([])

const selected = computed(() => {
    const url = usePage().props.value.settings.url

    for (const prefixGroup of prefixGroups) {
        if (url.startsWith(prefixGroup.prefix)) {
            return [prefixGroup.group]
        }
    }
    return []
})
const opened = ref(selected.value)

watch([openedN, selected], () => {
    opened.value = openedN.value.length > 0 ? openedN.value : []
})
const select = ref({
    language: localStorage.getItem('langTitle') || 'Česky',
    ISO: localStorage.getItem('lang') || 'cs',
    image: localStorage.getItem('langImage') || czechFlag
})

const languages = [
    { language: 'Česky', ISO: 'cs', image: czechFlag },
    {
        language: 'English',
        ISO: 'en',
        image: britishFlag
    }
]

const changeLanguage = () => {
    Inertia.post(route('language', { language: select.value.ISO }))
    localStorage.setItem('langTitle', select.value.language)
    localStorage.setItem('lang', select.value.ISO)
    localStorage.setItem('langImage', select.value.image)
    loadLanguageAsync(select.value.ISO)
}
const itemProps = (item) => {
    return {
        title: item.language,
        ISO: item.ISO,
        image: item.image
    }
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
    .v-list-item {
        padding-inline-start: 1em !important;
        padding: 0.7em !important;
    }
}

.active {
    background: #9ec5fe !important;
    color: white !important;
}

.v-list-item .v-list-item--active {
    background: #9ec5fe !important;
    color: white !important;
}

.v-footer {
    .move {
        transition: 0.8s;
        margin-left: 255px;
    }

    .move-back {
        transition: 0.8s;
    }
}
</style>
