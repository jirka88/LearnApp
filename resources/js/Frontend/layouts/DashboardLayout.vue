<template>
    <v-layout>
        <v-navigation-drawer
            v-model="drawer"
            :permanent="permanent"
            location="left"
        >
            <div class="usr">
                <Link :href="route('user.info')" class="text-decoration-none text-black">
                    <v-list-item
                        prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
                        :title="this.$page.props.user.firstname"
                        nav
                        height="64"
                        class=" d-flex align-center"
                    >
                        <div class="text-subtitle-2">{{ this.$page.props.user.email }}</div>
                    </v-list-item>
                </Link>
            </div>
            <v-divider></v-divider>
            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
                <Link v-if="this.$page.props.permission.view" :href="route('admin')">
                    <v-list-item prepend-icon="mdi-account-cog" title="Všichni uživatelé"
                                 value="Všichni uživatelé"></v-list-item>
                </Link>
                <v-list-group id="group">
                    <template v-slot:activator="{ props }">
                        <v-list-item
                            v-bind="props"
                            prepend-icon="mdi-account-circle"
                            :title="this.$page.props.user.typeAccount == 'Osobní' ? 'Sekce' : 'Předměty'"
                        >
                        </v-list-item>
                    </template>
                    <Link :href="route('subject.index')">
                        <v-list-item
                            class="subItem"
                            prepend-icon="mdi-folder-edit"
                            title="Organizace">
                        </v-list-item>
                    </Link>
                    <Link v-for="subject in this.$page.props.user.subjects" :key="subject.id" :href="route('subject.show', subject.slug)">
                    <v-list-item
                                 class="subItem"
                                 :prepend-icon="subject.icon"
                                 :title="subject.name">
                    </v-list-item>
                    </Link>
                </v-list-group>
                <Link :href="route('user.info')">
                    <v-list-item prepend-icon="mdi-account-cog" title="Nastavení profilu"
                                 value="Nastavení profilu"></v-list-item>
                </Link>
                <Link :href="route('user.report')">
                    <v-list-item prepend-icon="mdi-alert" title="Nahlásit chybu" value="Nahlásit chybu"></v-list-item>
                </Link>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar clipped-left>
            <v-btn v-if="$vuetify.display.lgAndUp" :icon="!drawer ? 'mdi-chevron-left' : 'mdi-chevron-right'"
                   @click.stop="drawer = !drawer"></v-btn>
            <v-btn v-if="$vuetify.display.mdAndDown" icon="mdi-format-list-bulleted"
                   @click.stop="drawer = !drawer"></v-btn>
            <v-spacer></v-spacer>
            <Link :href="route('logout')">
                <v-btn icon>
                    <v-icon>mdi-export</v-icon>
                </v-btn>
            </Link>
        </v-app-bar>
        <v-main>
            <slot>

            </slot>
        </v-main>
    </v-layout>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
const drawer = ref(true);
</script>

<style lang="scss">
.usr {
    .v-list-item-title {
        font-size: 1.2em !important;
    }
}

.v-app-bar {
    .v-icon {
        color: black !important;
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
</style>
