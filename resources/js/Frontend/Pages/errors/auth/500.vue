<script setup>
import err from './../../../../../assets/ui/errors/404-error.svg'
</script>
<template>
    <v-container>
        <div class="err text-center bigHeader py-12">
            <v-img class="margin-center" max-width="300" :src="err"></v-img>
            <h1>{{ $t('errors.title') }}</h1>
            <v-divider class="py-2"></v-divider>
            <p class="text-subtitle-1 font-weight-bold">
                {{ $t('errors.500.description') }}
            </p>
            <p class="text-subtitle-2">{{ $t('errors.500.solve') }}</p>
        </div>
    </v-container>
</template>

<style scoped lang="scss"></style>
