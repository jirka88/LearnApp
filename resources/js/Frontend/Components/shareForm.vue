<template>
<v-dialog
    v-model="active"
    transition="dialog-bottom-transition">
    <v-card>
        <v-form @submit.prevent="changeShare" class="d-flex flex-column ga-4">
            <v-text-field
                v-model="form.email"
                label="Email:"
                variant="outlined"
                disabled
                hide-details
            >
            </v-text-field>
            <v-select
                v-model="form.permission"
                label="Oprávnění:"
                :items="items"
                item-title="permission"
                item-value="id"
                persistent-hint
                return-object
                single-line
                variant="outlined"
                hide-details
            >
            </v-select>
            <div class="d-flex justify-end ga-2">
                <v-btn type="submit" @click="emit('close')">
                    {{$t('global.close')}}
                </v-btn>
            <v-btn type="submit" color="blue">
                {{$t('global.change')}}
            </v-btn>
            </div>
        </v-form>
    </v-card>
</v-dialog>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";

const props = defineProps({active: Boolean, detail: Object, permission: Array})
const active = props.active;
const items = ref(props.permission);
const emit = defineEmits(['close']);


const form = useForm({
    email: props.detail.user.email,
    permission: {'permission': props.detail.user.permission.name, 'id': props.detail.user.permission.permission_id},
    subject: props.detail.subject.id
});

const changeShare = () => {
    form.put(route('share.edit'), {
        onFinish() {
            emit('close')
        }
    });
}
</script>
<style scoped lang="scss">

</style>
