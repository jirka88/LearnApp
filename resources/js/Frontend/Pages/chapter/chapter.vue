<template>
    <component :is="DashboardLayout">
        <div class="chapterBg">
            <v-container class="py-12">
                <div class="chapter elevation-20" :class="$vuetify.display.smAndDown ? 'pa-6' : 'pa-10'">
                    <div class="d-flex ga-8 pb-4 overflow-auto">
                        <ChapterSettings
                            :chapter="chapter"
                            :slug="slug">
                        </ChapterSettings>
                        <ExportBtns :showExport="['pdf']" :disabledExport="disabledExport"
                                    @exportFile="exportFile"></ExportBtns>
                    </div>
                    <v-divider></v-divider>
                    <p class="text-h2 py-4 font-weight-bold">{{ chapter.name }}</p>
                    <p class="text-h6 py-2">{{ chapter.perex }}</p>
                    <v-divider class="py-2"></v-divider>
                    <p class="text-left" v-html="chapter.context"></p>
                </div>
            </v-container>
        </div>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {ref} from "vue";
import ChapterSettings from "@/Frontend/Components/ChapterSettings.vue";
import ExportBtns from "@/Frontend/Components/ExportBtns.vue";
import FileSaver from 'file-saver'
import axios from "axios";

const disabledExport = ref(false);
const props = defineProps({chapter: Object, slug: String})
const exportFile = async (value) => {
    disabledExport.value = true;
    await axios.post(`/dashboard/manager/subject/${props.slug}/chapter/${props.chapter.slug}/export?export=${value}`, {}, {
        responseType: 'blob'
    }).then((response) => {
        FileSaver.saveAs(response.data, props.chapter.slug);
        disabledExport.value = false;
    });
}

</script>

<style scoped lang="scss">

.chapterBg {
    background: #4398f0 !important;

    .chapter {
        border-radius: 24px;
        background: white !important;
        min-height: calc(100vh - 112px - 32px);

        h2 {
            text-wrap: balance;
        }
    }
}

:deep(.ql-syntax) {
    background: black;
    padding: 2em;
    border-radius: 24px;
    color: white;
}
</style>
