<script setup>
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import Breadcrumbs from '@/Frontend/Components/UI/Breadcrumbs.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import inertia from '@inertiajs/inertia'
import { useDialogDeleteStore } from '../../../states/dialogDeleteData'
const dialogDeleteStore = useDialogDeleteStore()
const props = defineProps({ data: Array, pages: Number })
const page = ref(1)

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
</script>

<template>
    <component :is="DashboardLayout">
        <v-container>
            <Breadcrumbs
                :items="[{ title: 'Log', disabled: true }]"
            ></Breadcrumbs>
            <v-table class="text-left py-8">
                <thead>
                    <tr class="pa-4">
                        <th class="font-weight-bold">Událost:</th>
                        <th class="font-weight-bold">Způsobil:</th>
                        <th class="font-weight-bold">Kdy:</th>
                        <th class="font-weight-bold">Aktivity:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="pa-8" v-for="log in data.data" :key="log.id">
                        <td>
                            {{ log.description }}
                        </td>
                        <td>
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
                            <Link @click="deleteDialog(log)">
                                <v-btn class="bg-red" icon="mdi-trash-can">
                                </v-btn>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </v-table>

            <v-pagination
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
