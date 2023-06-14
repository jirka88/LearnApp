<script setup>
import {Link} from "@inertiajs/inertia-vue3"
</script>

<template>
<p>LOL</p>
    <Link :href="route('logout')" method="post">Odhlásit</Link>
    {{$page.props.user.firstname}}
    {{$page.props.user.email}}
    {{$page.props.user}}
</template>

<style scoped lang="scss">

</style>
