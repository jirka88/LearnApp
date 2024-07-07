<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { useDialogDeleteStore } from '../../../../states/dialogDeleteData'

const dialogDeleteStore = useDialogDeleteStore()

const dialog = computed(() => dialogDeleteStore.dialog)
const object = computed(() => dialogDeleteStore.object)
const url = computed(() => dialogDeleteStore.url)
const urlParams = computed(() => dialogDeleteStore.urlParams)
const link = computed(() => dialogDeleteStore.link)
const disabledBtn = ref(false)
const destroy = () => {
    disabledBtn.value = true
    if (Object.keys(object.value).length !== 0) {
        Inertia.delete(route(url.value, object.value.id))
    } else {
        Inertia.delete(route(url.value, urlParams.value))
    }
    dialogDeleteStore.setDefault()
    disabledBtn.value = false
}
</script>

<template>
    <v-dialog v-model="dialog" :key="object.id">
        <v-card>
            <v-card-title class="text-h5 text-center">
                Opravdu si přejete smazat tuto položku?
            </v-card-title>
            <v-card-text class="text-center pa-2"
                >Tato akce je nenávratná.
            </v-card-text>
            <v-card-actions class="margin-center d-flex ga-2">
                <v-btn
                    class="bg-white rounded"
                    @click="() => dialogDeleteStore.setOnlyDialog(false)"
                    size="x-large"
                >
                    {{$t('global.close')}}
                </v-btn>
                <Link v-if="link">
                    <v-btn
                        class="bg-red rounded"
                        @click="destroy"
                        size="x-large"
                        :disabled="disabledBtn"
                    >
                        {{$t('global.delete')}}
                    </v-btn>
                </Link>
                <v-btn
                    v-else
                    class="bg-red rounded"
                    @click="destroy"
                    size="x-large"
                    :disabled="disabledBtn"
                >
                    {{$t('global.delete')}}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped lang="scss">
.v-card-title {
    white-space: unset;
}
</style>
