<script setup>
import { mdiFormatListBulletedSquare } from '@mdi/js';
import {Link} from "@inertiajs/inertia-vue3"
import {ref} from "vue";
const drawer = ref(true);
const rail = ref(false);
</script>

<template>
        <v-layout>
            <v-navigation-drawer
                v-model="drawer"
                :rail="rail"
                permanent
                @click="rail = false"
            >
                <v-list-item
                    prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
                    :title="$page.props.user.firstname"
                    nav
                    height="64"
                >
                </v-list-item>

                <v-divider></v-divider>

                <v-list density="compact" nav>
                    <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
                </v-list>
            </v-navigation-drawer>
                <v-app-bar>
                    <v-btn :icon="!rail ? 'mdi-chevron-left' : 'mdi-chevron-right'" @click.stop="rail = !rail"></v-btn>
                    <v-spacer></v-spacer>

                    <v-btn icon>
                        <Link :href="route('logout')" method="post"><v-icon>mdi-export</v-icon></Link>
                    </v-btn>
                </v-app-bar>

        </v-layout>

<!--<p>LOL</p>
    <Link :href="route('logout')" method="post">Odhlásit</Link>
    {{$page.props.user.firstname}}
    {{$page.props.user.email}}
    {{$page.props.user}}-->
</template>

<style lang="scss">
.v-app-bar{
    .v-icon {
        color: black !important;
    }
}
</style>
