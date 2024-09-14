<script setup>
const props = defineProps({ title: String, text: String })
</script>

<template>
    <div>
        <h3 class="pb-1" :class="{ 'text-center': $vuetify.display.smAndDown }">
            {{ title }}
        </h3>
        <p
            class="text-grey-darken-1"
            :class="{ 'text-center': $vuetify.display.smAndDown }"
        >
            {{ text }}
        </p>
    </div>
</template>

<style scoped lang="scss">
h3 {
    letter-spacing: 0.05em !important;
}
</style>
