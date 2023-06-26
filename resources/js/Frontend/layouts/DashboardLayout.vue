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
                        <div class="text-subtitle-2">{{this.$page.props.user.email}}</div>
                    </v-list-item>
                </Link>
            </div>
            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
                <v-list-item prepend-icon="mdi-account-settings"  :href="route('user.info')" title="Nastavení profilu" value="Nastavení profilu"></v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar  clipped-left>
            <v-btn v-if="$vuetify.display.lgAndUp" :icon="!drawer ? 'mdi-chevron-left' : 'mdi-chevron-right'" @click.stop="drawer = !drawer"></v-btn>
            <v-btn v-if="$vuetify.display.mdAndDown" icon="mdi-format-list-bulleted" @click.stop="drawer = !drawer"></v-btn>
            <v-spacer></v-spacer>
            <v-btn icon >
                <Link :href="route('logout')"><v-icon>mdi-export</v-icon></Link>
            </v-btn>
        </v-app-bar>
        <v-main>
                <slot>

                </slot>
        </v-main>
    </v-layout>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3"
import {ref} from "vue";
const drawer = ref(true);
</script>

<style lang="scss">
.usr {
    .v-list-item-title {
        font-size: 1.2em !important;
    }
}
    .v-app-bar{
        .v-icon {
            color: black !important;
        }
    }
</style>
