<template>
    <component :is="DashboardLayout">
        <v-container>
            <Breadcrumbs
                :items="[{ title: 'Nové sdílení', disabled: true }]"
            ></Breadcrumbs>
            <v-main
                class="pa-5 pl-0 d-flex flex-wrap"
                :class="{ 'justify-center': $vuetify.display.smAndDown }"
            >
                <v-card
                    v-for="subject in subjects"
                    :key="subject.id"
                    class="pa-2 elevation-10 rounded-8"
                    data-aos="zoom-in"
                    data-aos-delay="200"
                    data-aos-duration="300"
                    data-aos-anchor-placement="top-bottom"
                    :max-width="$vuetify.display.smAndDown ? '' : '30em'"
                >
                    <v-card-text class="pb-0">
                        <p class="text-h4 font-weight-bold">
                            {{ subject.name }}
                        </p>
                        <p class="text-subtitle-1 pt-2">
                            {{ $t('share.requested_by') }}
                            <span class="font-weight-bold">{{
                                subject.users[0].email
                            }}</span>
                        </p>
                        <div class="text-subtitle-1">
                            <p v-if="subject.permission.permission_id == 1">
                                {{ $t('share.permission_view.read') }}<br />
                            </p>
                            <p
                                v-else-if="
                                    subject.permission.permission_id == 2
                                "
                            >
                                {{ $t('share.permission_view.write') }}<br />
                            </p>
                            <p v-else>
                                {{ $t('share.permission_view.full') }}<br />
                            </p>
                        </div>
                    </v-card-text>
                    <v-card-actions
                        class="flex-wrap justify-end align-center ga-2"
                    >
                        <v-btn
                            variant="flat"
                            color="red"
                            :disabled="btnDisabled"
                            @click="shareDelete(subject.slug)"
                        >
                            {{ $t('global.close') }}
                        </v-btn>
                        <v-btn
                            variant="flat"
                            color="green"
                            :disabled="btnDisabled"
                            @click="shareAccept(subject.slug)"
                        >
                            {{ $t('share.accepted') }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-main>
        </v-container>
    </component>
</template>

<script setup>
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import Breadcrumbs from '@/Frontend/Components/UI/Breadcrumbs.vue'

const props = defineProps({ subjects: Object })
const form = useForm()
const btnDisabled = ref(false)
const disabledbtn = () => {
    btnDisabled.value = true
}

const shareDelete = (slug) => {
    disabledbtn()
    form.delete(route('share.delete', slug), {
        onSuccess: () => {
            btnDisabled.value = false
        }
    })
}

const shareAccept = (slug) => {
    disabledbtn()
    const form2 = useForm({
        slug: slug
    })
    form2.post(route('share.accept'), {
        onSuccess: () => {
            btnDisabled.value = false
        }
    })
}
</script>

<style scoped lang="scss"></style>
