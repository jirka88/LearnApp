<script setup>
defineProps({countTd: Number})
</script>

<template>
    <tr class="pa-8">
        <td v-for="n in countTd" :key="n">
            <v-skeleton-loader
                type="list-item"
            ></v-skeleton-loader>
        </td>
    </tr>
</template>

<style scoped lang="scss">

</style>
