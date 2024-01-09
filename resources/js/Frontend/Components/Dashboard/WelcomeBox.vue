<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import undefinedProfilePicture from "../../../../assets/user/Default_pfp.svg";
</script>

<template>
    <v-sheet
        :elevation="8"
        :height="80"
        border
        rounded
        class="info align-center"
    >
        <v-img
            class="margin-center"
            :src="$page.props.user.image ? '/storage/' + $page.props.user.image : undefinedProfilePicture"></v-img>
        <div>
            <p class="font-weight-bold text-h6">{{$t('dashboard.welcome')}},
                {{ $page.props.user.firstname }}</p>
            <Link :href="route('logout')">{{$t('dashboard.sign_out')}}</Link>
        </div>
    </v-sheet>
</template>

<style scoped lang="scss">
.v-img {
    border-radius: 50%;
    max-height: 4em;
    width: 4em;
}
.info {
    display: grid;
    grid-template-columns: 6em 1fr;
    grid-template-rows: 1fr;
}
.text-h6 {
    line-height: 1.2em;
}
</style>
