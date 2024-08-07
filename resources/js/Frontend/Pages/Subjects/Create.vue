<template>
    <component :is="SubjectManagerLayout">
        <v-form
            class="pa-6 bg-white"
            :class="{ 'w-85': $vuetify.display.smAndDown }"
            @submit.prevent="createSubject"
        >
            <p class="text-h1 font-weight-bold py-6">
                {{ $t('global.created') }}
                {{
                    $page.props.user.typeAccount == 'Osobní'
                        ? 'sekci'
                        : 'předmět'
                }}
            </p>
            <div class="d-flex flex-column ga-2">
                <v-text-field
                    v-model="form.name"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                    autofocus
                    :label="$t('global.name')"
                    :rules="[
                        rules.required,
                        rules.chapterNameLength,
                        rules.minSubjectLength
                    ]"
                ></v-text-field>
                <v-select
                    variant="outlined"
                    v-model="form.icon"
                    :items="icons"
                    item-title="iconName"
                    label="Ikona"
                >
                    <template v-slot:selection="{ item, index }">
                        <div
                            class="d-flex justify-content-center align-items-center ga-2"
                        >
                            <v-icon :icon="item.title"></v-icon>
                            {{ item.title }}
                        </div>
                    </template>
                    <template v-slot:item="{ item, props }">
                        <v-list-item v-bind="props">
                            <template #title>
                                <div
                                    class="d-flex justify-content-center align-items-center ga-2"
                                >
                                    <v-icon :icon="item.title"></v-icon>
                                    {{ item.title }}
                                </div>
                            </template>
                        </v-list-item>
                    </template>
                </v-select>
            </div>
            <v-btn
                type="submit"
                color="blue"
                class="d-flex justify-center margin-center"
                :disabled="creating"
                :class="{ 'w-100': $vuetify.display.smAndDown }"
            >
                {{ $t('global.created') }}!
            </v-btn>
        </v-form>
    </component>
</template>
<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import icons from '../../../itemsIcons'
import SubjectManagerLayout from '@/Frontend/layouts/SubjectManagerLayout.vue'
import { ref } from 'vue'
import rules from './../../rules/rules'
import { toastShow } from '@/Toast'

const props = defineProps({ usr: Object, url: String, errors: Object })
const creating = ref(false)
const form = useForm({
    name: '',
    icon: 'mdi-text-long'
})

const createSubject = async () => {
    creating.value = true
    if (props.url === undefined) {
        form.post('/dashboard/manager/subject/', {
            onSuccess: () => {
                form.reset()
            },
            onError: () => {
                toastShow(true)
                creating.value = false
            }
        })
        return
    }
    form.post(props.url, {
        onSuccess: () => {
            form.reset()
        },
        onError: () => {
            toastShow(true)
            creating.value = false
        }
    })
}
</script>
<style scoped lang="scss">
.v-form {
    width: inherit;
    max-width: 35em !important;
    border-radius: 1em;
    border: 1px solid gray;
    box-shadow: 1em 1em gray;
}

p:first-child {
    font-size: 1.8em !important;
}
</style>
