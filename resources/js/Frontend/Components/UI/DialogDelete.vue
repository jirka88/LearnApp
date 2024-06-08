<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed } from 'vue'
import { useDialogDeleteStore } from '../../../../states/dialogDeleteData'
const dialogDeleteStore = useDialogDeleteStore()

const dialog = computed(() => dialogDeleteStore.dialog)
const object = computed(() => dialogDeleteStore.object)
const url = computed(() => dialogDeleteStore.url)
const destroy = (id) => {
    Inertia.delete(route(url.value, id))
    dialogDeleteStore.setDefault()
}
</script>

<template>
    <v-dialog v-model="dialog" :key="object.id">
        <v-card>
            <v-card-title class="text-h5 text-center">
                Opravdu si přejete smazat tuto položku?
            </v-card-title>
            <v-card-text class="text-center"
                >Tato akce je nenávratná.</v-card-text
            >
            <v-card-actions class="margin-center">
                <v-spacer></v-spacer>
                <v-btn
                    class="bg-white"
                    @click="() => dialogDeleteStore.setOnlyDialog(false)"
                    size="x-large"
                >
                    Zřušit
                </v-btn>
                <v-btn
                    class="bg-red"
                    @click="destroy(object.id)"
                    size="x-large"
                >
                    Smazat!
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped lang="scss"></style>
