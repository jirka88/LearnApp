<template>
    <v-dialog
        v-if="subject.created_by == $page.props.user.id"
        persistent
        width="auto"
    >
        <v-form @submit.prevent="sharingToUsers(); $page.props.flash.messageUpdate = ''">
            <v-card>
                <v-card-title class="text-h5 text-center">
                    {{$t('share.title')}}
                </v-card-title>
                <v-autocomplete
                    v-model="selectedUsers"
                    chips
                    variant="outlined"
                    label="Emailová adresa uživatelů"
                    :items="users.map(item => item.email)"
                    multiple
                    :error="errors.users"
                    :error-messages="errors.users"
                    class="pa-2"
                >
                </v-autocomplete>
                <p v-if="errors.permission" class="text-center pa-1 text-red">{{ errors.permission }}</p>
                <div class="d-flex">
                    <v-checkbox
                        v-model="permission"
                        :label="$t('share.permission.read')"
                        value="1"
                        hide-details>
                    </v-checkbox>
                    <v-checkbox
                        v-model="permission"
                        :label="$t('share.permission.write')"
                        value="2"
                        hide-details>
                    </v-checkbox>
                    <v-checkbox
                        v-model="permission"
                        :label="$t('share.permission.full')"
                        value="3"
                        hide-details>
                    </v-checkbox>
                </div>
                <v-card-text class="text-center pa-1">{{$t('share.subtitle')}}
                </v-card-text>
                <v-card-actions class="margin-center d-flex justify-center">
                    <v-btn
                        class="bg-white"
                        @click="emit('close'); $page.props.flash.messageUpdate = ''"
                        size="x-large"
                    >
                       {{$t('global.close')}}
                    </v-btn>
                    <v-btn
                        type="submit"
                        class="bg-orange"
                        size="x-large"
                    >
                        {{$t('userAccount.share')}}!
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>
<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import axios from "axios";
import {onBeforeMount, onMounted, ref, watch} from "vue";

const props = defineProps({subject: Object, errors: Object, users: Object })
const emit = defineEmits(['close'])
const selectedUsers = ref();
const permission = ref();

const form = useForm({
    users: selectedUsers,
    permission: permission,
    subject: props.subject.id
});


const rules = {
    required: v => v.length < 0 || "Musíte zadat uživatele",
}

const sharingToUsers = () => {
    form.post(route('share'), {
        onSuccess: () => {
            selectedUsers.value = null;
        }
    })
}
</script>
<style scoped lang="scss">

</style>
