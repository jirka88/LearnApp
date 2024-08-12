<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({ text: String, variant: String, time: Number })
const emit = defineEmits(['close'])
const toast = ref(true)
const closed = () => {
    toast.value = false
}

onMounted(() => {
    setTimeout(() => (toast.value = false), props.time ? props.time : 1000)
})
const handleAfterLeave = () => {
    emit('close')
}
</script>

<template>
    <Transition
        name="bounce"
        mode="out-in"
        @after-leave="handleAfterLeave"
        appear
    >
        <v-alert
            v-if="toast"
            height="70"
            min-width="300"
            width="300"
            :color="variant"
            :icon="'$' + variant"
            class="position-fixed toastify px-4"
            :title="$t(text)"
            prominent
            :class="{ center: $vuetify.display.mdAndDown }"
        >
            <v-btn
                @click="closed"
                variant="plain"
                :ripple="false"
                icon="mdi-window-close"
                class="justify-end align-start"
            >
            </v-btn>
        </v-alert>
    </Transition>
</template>

<style scoped lang="scss">
.toastify {
    border-radius: 1em;
    right: 5%;
    top: 2%;
    z-index: 9999;
    padding-top: 0.8em;

    :deep(.v-alert__content) {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;

        .v-alert-title {
            font-size: 0.8em;
            line-height: 1.2em;
            font-weight: bold;
        }
    }

    :deep(.v-icon) {
        font-size: 2em !important;
    }

    .v-btn {
        :deep(.v-icon) {
            font-size: 1.4em !important;
        }
    }
}

.center {
    transform: translate(-50%, -50%);
    left: 50%;
    top: 5%;
}

.bounce-enter-active {
    animation: bounce-in 0.5s;
}

.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}

@keyframes bounce-in {
    from {
        top: -10%;
    }
    to {
        top: 2%;
    }
}
</style>
