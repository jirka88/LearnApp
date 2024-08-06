<script setup>
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({ sharingUsr: Object, subject: Object })
import { ref } from 'vue'
const show = ref(false)
const removeShare = () => {
    Inertia.delete(route('share.delete', props.subject.slug))
}
</script>

<template>
    <v-card
        class="pa-4 font-weight-bold d-flex align-center"
        :class="{
            'text-center flex-wrap justify-center ga-4': $vuetify.display.smAndDown
        }"
    >
        <p class="flex-grow-1">
            Sdíleno od: {{ sharingUsr.firstname }} {{ sharingUsr.lastname }} ({{
                sharingUsr.email
            }})
        </p>
        <v-tooltip v-model="show" location="bottom">
            <template v-slot:activator="{ props }">
                <v-btn
                    color="red"
                    icon="mdi-trash-can"
                    @click="removeShare"
                    v-bind="props"
                >
                </v-btn>
            </template>
            <span>Zrušit sdílení</span>
        </v-tooltip>
    </v-card>
</template>

<style scoped lang="scss"></style>
