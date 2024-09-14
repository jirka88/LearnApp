<script setup>
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <v-btn
        type="submit"
        color="blue"
        class="btn d-flex ml-auto"
        :disabled="disabled"
        :class="{ 'w-100': $vuetify.display.smAndDown }"
    >
        {{ $t('global.edit') }}
    </v-btn>
</template>

<style scoped lang="scss"></style>
