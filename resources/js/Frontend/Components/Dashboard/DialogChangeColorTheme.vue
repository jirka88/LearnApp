<template>
    <v-dialog>
        <v-card>
            <v-card-title class="text-h5 text-center font-weight-bold py-2">
                Nastavení barvy aplikace:
            </v-card-title>
            <div class="justify-center d-flex py-6">
            <v-color-picker
                v-model="picker"
                elevation="5"
            ></v-color-picker>
            </div>
            <v-card-actions class="margin-center">
                <v-spacer></v-spacer>
                <v-btn
                    class="bg-white"
                    @click="close"
                    size="x-large"
                    variant="outlined"
                >
                    {{$t('global.close')}}
                </v-btn>
                <v-btn
                    class="bg-red"
                    size="x-large"
                    @click="setTheme"
                >
                    {{$t('global.change')}}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup>

import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import { usePage } from '@inertiajs/inertia-vue3'

const emit = defineEmits(['close']);
const picker = ref((usePage().props.value.settings.theme.color))
const close = () => {
    emit('close');
}
const setTheme = () => {
    Inertia.put(route('admintheme.color', {color: picker.value}), {}, {
        onFinish: () => {
            close();
        }
    });
}
</script>
