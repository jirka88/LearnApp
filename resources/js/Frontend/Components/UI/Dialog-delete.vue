<template>
        <v-card>
            <v-card-title class="text-h5 text-center">
                Opravdu si přejete smazat {{Type}} <strong>{{Obj.firstname}}</strong>
            </v-card-title>
            <v-card-text class="text-center">Tato akce je nenávratná. {{Description}}</v-card-text>
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
                    @click="destroy(Obj.id)"
                    size="x-large"
                >
                    Smazat!
                </v-btn>
            </v-card-actions>
        </v-card>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({ Path: String, Obj: Object, Type: String, Description: String} )
const emit = defineEmits(['close']);
const close = () => {
    emit('close');
}
const destroy = (id) =>{
    Inertia.delete(route(props.Path, id));
    emit('close');
}
</script>

<style scoped lang="scss">

</style>
