<script setup>
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
            permanent="false"
            @click="rail = false"
        >
            <div class="usr">
                <Link :href="route('user.info')" class="text-decoration-none text-black">
                    <v-list-item
                        prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
                        :title="$page.props.user.firstname"
                        nav
                        height="64"
                        class=" d-flex align-center"
                    >
                        <div class="text-subtitle-2">{{$page.props.user.email}}</div>
                    </v-list-item>
                </Link>
            </div>
            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar>
            <v-btn :icon="!rail ? 'mdi-chevron-left' : 'mdi-chevron-right'" @click.stop="rail = !rail"></v-btn>
            <v-spacer></v-spacer>
            <v-btn icon>
                <Link :href="route('logout')" @click="((e) => e.prevent.default())" method="post"><v-icon>mdi-export</v-icon></Link>
            </v-btn>
        </v-app-bar>
        <v-main>
            <v-card-text>
                <slot>

                </slot>
            </v-card-text>
        </v-main>
    </v-layout>
</template>

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
