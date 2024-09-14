<template>
    <v-sheet class="pa-8" rounded="xl" elevation="2">
        <v-form @submit.prevent="form.put(route('user.updateRole', { id: usr.id }))">
            <div class="text-subtitle-1 text-medium-emphasis">Role:</div>
            <v-select
                v-model="form.role"
                :items="roles"
                item-title="role"
                item-value="id"
                label="Select"
                persistent-hint
                return-object
                single-line
                variant="outlined"
            ></v-select>
            <BtnSetting
                :disabled="$page.props.permission.basic_view || usr.roles.id === 1"
            >
            </BtnSetting>
        </v-form>
    </v-sheet>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import BtnSetting from '@/Frontend/Components/BtnSetting.vue'
import { computed } from 'vue'

const props = defineProps({ usr: Object, roles: Array })

const userRole = computed(() => {
    const language = 'role_' + usePage().props.value.user.set_language
    return props.usr.roles[language]
})
const form = useForm({
    role: {
        role: userRole.value,
        id: props.usr.roles.id
    }
})

const permission = (permissionView, userId) => {
    if (permissionView) {
        return props.usr.roles.id === 1
    }
    return true
}
</script>

<style scoped lang="scss"></style>
