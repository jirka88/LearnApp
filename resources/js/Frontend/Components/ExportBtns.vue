<script setup>
import {ref} from "vue";

const emit = defineEmits(['exportFile'])
const props = defineProps({disabledExport: Boolean, showExport: Array})

</script>

<template>
    <div class="d-flex flex-column ga-1">
        <div>
            <p class="text-subtitle-2">Exporty:</p>
            <v-divider></v-divider>
        </div>
        <div class="d-flex ga-4">
            <v-btn
                v-if="showExport.find(item => item === 'pdf')"
                min-width="1em"
                max-height="3.6em"
                prepend-icon="mdi-file-pdf-box"
                stacked
                :loading="disabledExport"
                :disabled="disabledExport"
                @click="emit('exportFile', 'pdf')">

            </v-btn>
            <v-btn
                v-if="showExport.find(item => item === 'excel')"
                min-width="2em"
                max-height="3.6em"
                prepend-icon="mdi-file-excel"
                stacked
                :loading="disabledExport"
                :disabled="disabledExport"
                @click="emit('exportFile', 'xlsx')">

            </v-btn>
            <v-btn
                v-if="showExport.find(item => item === 'csv')"
                min-width="2em"
                max-height="3.6em"
                :loading="disabledExport"
                :disabled="disabledExport"
                prepend-icon="mdi-file"
                stacked
                @click="emit('exportFile', 'csv')">
                csv
            </v-btn>
            <v-btn
                v-if="showExport.find(item => item === 'html')"
                min-width="2em"
                max-height="3.6em"
                prepend-icon="mdi-language-html5"
                stacked
                :loading="disabledExport"
                :disabled="disabledExport"
                @click="emit('exportFile', 'html')">

            </v-btn>
            <v-btn
                v-if="showExport.find(item => item === 'xml')"
                min-width="2em"
                max-height="3.6em"
                prepend-icon="mdi-xml"
                stacked
                :loading="disabledExport"
                :disabled="disabledExport"
                @click="emit('exportFile', 'xml')">

            </v-btn>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
