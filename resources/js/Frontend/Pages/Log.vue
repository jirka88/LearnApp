<script setup>
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import Breadcrumbs from '@/Frontend/Components/UI/Breadcrumbs.vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({ data: Array })
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
                    <tr class="pa-8" v-for="log in data" :key="log.id">
                        <td>
                            {{ log.description }}
                        </td>
                        <td>
                            {{ log.causedBy?.email }}
                        </td>
                        <td>
                            {{ log.created_at }}
                        </td>
                        <td class="d-flex ga-2">
                            <Link :href="route('adminlog.show', log.id)">
                                <v-btn class="bg-green" icon="mdi-open-in-new">
                                </v-btn>
                            </Link>
                            <Link>
                                <v-btn class="bg-red" icon="mdi-trash-can">
                                </v-btn>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-container>
    </component>
</template>

<style scoped lang="scss"></style>
