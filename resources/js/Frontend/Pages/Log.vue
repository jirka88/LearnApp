<script setup>
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import Breadcrumbs from '@/Frontend/Components/UI/Breadcrumbs.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import inertia from '@inertiajs/inertia'
import { useDialogDeleteStore } from '../../../states/dialogDeleteData'
import ExportBtns from '@/Frontend/Components/ExportBtns.vue'
import axios from 'axios'
import FileSaver from 'file-saver'

const dialogDeleteStore = useDialogDeleteStore()
const props = defineProps({ data: Array, pages: Number })
const page = ref(1)
const disabledExport = ref(false)
const fetchData = () => {
    inertia.Inertia.get(
        route('adminlog'),
        { page: page.value },
        {
            preserveState: true,
            onSuccess: (response) => {
                props.data = response.props.data
            }
        }
    )
}
const deleteDialog = (log) => {
    dialogDeleteStore.setDialog(true, log, 'adminlog.destroy')
}
const exportFile = async (value) => {
    disabledExport.value = true
    await axios
        .post(
            `/dashboard/admin/controll/log/export?export=${value}`,
            {},
            {
                responseType: 'blob'
            }
        )
        .then((response) => {
            FileSaver.saveAs(response.data, 'Log')
            disabledExport.value = false
        })
}
</script>

<template>
    <component :is="DashboardLayout">
        <v-container>
            <Breadcrumbs
                :items="[{ title: 'Log', disabled: true }]"
            ></Breadcrumbs>
            <ExportBtns
                :showExport="['pdf', 'excel', 'csv', 'html']"
                @exportFile="exportFile"
                :disabledExport="disabledExport"
                class="pt-6"
            >
            </ExportBtns>
            <v-table class="text-left py-8">
                <thead>
                    <tr class="pa-4">
                        <th class="font-weight-bold">Id:</th>
                        <th class="font-weight-bold">Událost:</th>
                        <th class="font-weight-bold">Způsobil:</th>
                        <th class="font-weight-bold">Kdy:</th>
                        <th class="font-weight-bold">Aktivity:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-if="data.data.length > 0"
                        class="pa-8"
                        v-for="log in data.data"
                        :key="log.id"
                    >
                        <td>{{ log.id }}</td>
                        <td>
                            {{ log.description }}
                        </td>
                        <td v-if="log.causer?.slug">
                            <Link
                                class="text-decoration-underline"
                                :href="route('adminuser.edit', log.causer.slug)"
                            >
                                {{ log.causer?.email }}
                            </Link>
                        </td>
                        <td>
                            {{ log.created_at }}
                        </td>
                        <td class="d-flex ga-2">
                            <Link :href="route('adminlog.show', log.id)">
                                <v-btn class="bg-green" icon="mdi-open-in-new">
                                </v-btn>
                            </Link>
                            <v-btn
                                @click="deleteDialog(log)"
                                class="bg-red"
                                icon="mdi-trash-can"
                            >
                            </v-btn>
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="4" class="text-center">
                            Žádný log k zobrazení!
                        </td>
                    </tr>
                </tbody>
            </v-table>

            <v-pagination
                v-if="data.data.length > 0"
                v-model="page"
                :length="pages"
                prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right"
                @update:modelValue="fetchData"
            ></v-pagination>
        </v-container>
    </component>
</template>

<style scoped lang="scss"></style>
