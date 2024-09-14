<script setup>
import setLanguage from '../../setLanguage'
import { defineAsyncComponent } from 'vue'
import { isActiveToast, toastShow } from '@/Toast'
import { useDialogDeleteStore } from '../../../states/dialogDeleteData'
import { usePage } from '@inertiajs/inertia-vue3'

const DialogDelete = defineAsyncComponent(
    () => import('@/Frontend/Components/AppDialogDelete.vue')
)

const Toastify = defineAsyncComponent(() => import('@/Frontend/Components/Toastify.vue'))
const dialogDeleteStore = useDialogDeleteStore()
setLanguage()

const closeToast = () => {
    toastShow(false)
    usePage().props.value.flash.message = ''
}
</script>

<template>
    <Toastify
        v-if="isActiveToast && $page.props?.errors?.msg && $page.props?.flash?.status"
        :text="$page.props.errors.msg"
        :variant="$page.props.flash.status"
        :time="3000"
        @close="closeToast"
    ></Toastify>
    <Toastify
        v-if="$page.props?.flash?.message && $page.props?.flash?.status"
        :text="$page.props.flash.message"
        :variant="$page.props.flash.status"
        :time="3000"
        @close="closeToast"
    ></Toastify>
    <Toastify
        v-if="$page.props?.errors?.message"
        :text="$page.props.errors.message"
        variant="error"
        :time="3000"
        @close="closeToast"
    ></Toastify>
    <DialogDelete v-if="dialogDeleteStore.url"></DialogDelete>
    <slot></slot>
</template>
