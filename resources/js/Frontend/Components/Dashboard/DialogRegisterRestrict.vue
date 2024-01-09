<template>
<v-dialog>
    <v-card>
        <v-card-title class="text-h5 text-center">
           {{restricted ? 'Opravdu si přejete omezit registraci?' : 'Opravdu si přejete povolit registraci?'}}
        </v-card-title>
        <v-card-text class="text-center">  {{restricted ? 'Při této akci se nový uživatelé nemohou registrovat do aplikaci.' : 'Při této akci se nový uživatelé mohou registrovat do aplikaci.'}}</v-card-text>
        <v-card-actions class="margin-center">
            <v-spacer></v-spacer>
            <v-btn
                class="bg-white"
                @click="close"
                size="x-large"
            >
                Zřušit
            </v-btn>
            <v-btn
                class="bg-red"
                size="x-large"
                @click="changeRestrict"
            >
                Změnit
            </v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
const props = defineProps({restricted: Boolean})
const emit = defineEmits(['close']);
const close = () => {
    emit('close');
}
const changeRestrict = () => {
    Inertia.put(route('adminregister.restriction',{register: props.restricted}), {},{
        onSuccess: () => {
           close();
        }
    });
}

</script>

<style scoped lang="scss">

</style>
