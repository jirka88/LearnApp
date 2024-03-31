<script setup>
import setLanguage from "../../setLanguage";
import {defineAsyncComponent} from "vue";
import {isActiveToast, toastShow} from "@/Toast";
const Toastify = defineAsyncComponent(() => import("@/Frontend/Components/UI/Toastify.vue"));
setLanguage();
</script>

<template>
    <Toastify v-if="isActiveToast && $page.props?.errors?.msg && $page.props?.flash?.status"
              :text="$page.props.errors.msg"
              :variant="$page.props.flash.status"
              :time="3000"
              @close="toastShow(false)"></Toastify>
    <Toastify v-if="$page.props?.flash?.message && $page.props?.flash?.status"
              :text="$page.props.flash.message"
              :variant="$page.props.flash.status"
              :time="3000"
              @close="toastShow(false)"></Toastify>
    <slot>

    </slot>
</template>
