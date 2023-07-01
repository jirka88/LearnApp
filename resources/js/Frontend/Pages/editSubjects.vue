<template>
<SubjectManagerLayout>
{{subject}}
</SubjectManagerLayout>
</template>
<script setup>
import SubjectManagerLayout from "@/Frontend/layouts/SubjectManagerLayout.vue";

defineProps({subject: Object})
</script>

<style scoped lang="scss">

</style>
