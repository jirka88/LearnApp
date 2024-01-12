<script setup>
import {onMounted, ref, watch} from "vue";

const props = defineProps({text: String, variant: String, time: Number})
const emit = defineEmits(["close"])
const toast = ref(true);
const closed = () => {
    emit('close');
}

 onMounted(() => {
   setTimeout(() => toast.value = false, props.time)
})
const handleAfterLeave = () =>{
    emit('close');
}
</script>

<template>
    <Transition name="bounce" mode="out-in" @after-leave="handleAfterLeave" appear>
    <v-sheet
        v-if="toast"
        height="70"
        min-width="300"
        width="300"
        class="position-fixed toastify px-4"
        :color="variant"
        ref="toast"
        :class="{'center': $vuetify.display.mdAndDown}">
        <div class="h-100 align-center ga-4">
            <v-icon
                icon="mdi-checkbox-marked-circle"
            ></v-icon>
            <p class="font-weight-bold text-white">{{text}}</p>
            <v-btn
                @click="closed"
                variant="plain"
                :ripple="false"
                icon="mdi-window-close"
                class="justify-end align-start"
            >
            </v-btn>
        </div>
    </v-sheet>
    </Transition>
</template>

<style scoped lang="scss">
.toastify {
    border-radius: 1em;
    right: 5%;
    top: 2%;
    z-index: 9999;
    div {
        display: grid;
        grid-template-columns: 1em 1fr 1em;
    }
}
.center {
    transform: translate(-50%,-50%);
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
