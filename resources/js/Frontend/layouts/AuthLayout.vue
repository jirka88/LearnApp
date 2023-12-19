<template>
    <Base>
    <div class="auth">
        <slot>

        </slot>
    </div>
    </Base>
</template>
<script setup>
import Base from "./../Pages/Base.vue"
</script>
<style scoped lang="scss">
.auth {
    background: url('./../../../assets/authentication/d.svg') no-repeat center;
    background-size: cover;
}
</style>
