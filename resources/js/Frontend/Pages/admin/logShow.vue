<script setup>
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import Breadcrumbs from '@/Frontend/Components/UI/Breadcrumbs.vue'
import { Link } from '@inertiajs/inertia-vue3'

defineProps({ activity: Object })
</script>

<template>
    <component :is="DashboardLayout">
        <v-container>
            <Breadcrumbs
                :items="[
                    { title: 'Log', disabled: false, href: route('adminlog') },
                    { title: activity.description, disabled: true }
                ]"
            ></Breadcrumbs>
            <v-sheet class="py-6 d-flex flex-column ga-8">
                <div class="text-h4 font-weight-bold">Informace o logu:</div>
                <p
                    class="font-weight-bold"
                    :class="{ 'text-center': $vuetify.display.smAndDown }"
                >
                    {{ activity.description }} v {{activity.created_at}}
                </p>
                <div
                    v-if="activity.properties.old"
                    class="d-flex align-center"
                    :class="{ 'flex-column': $vuetify.display.smAndDown }"
                >
                    <v-sheet class="d-flex flex-column data pa-6" elevation="3">
                        <p class="text-center font-weight-bold">
                            Starý záznam:
                        </p>
                        <v-table class="text-caption" density="compact">
                            <tbody
                                v-for="(value, key) in activity.properties.old"
                                :key="key"
                            >
                                <tr>
                                    <th class="font-weight-bold">{{ key }}:</th>
                                    <td>{{ value }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-sheet>
                    <v-icon
                        :icon="
                            $vuetify.display.smAndDown
                                ? 'mdi-arrow-down-bold'
                                : 'mdi-arrow-right-bold'
                        "
                        class="pa-8"
                    ></v-icon>
                    <v-sheet class="d-flex flex-column data pa-6" elevation="3">
                        <p class="text-center font-weight-bold">Nový záznam:</p>
                        <v-table class="text-caption" density="compact">
                            <tbody
                                v-for="(value, key) in activity.properties
                                    .attributes"
                                :key="key"
                            >
                                <tr>
                                    <th class="font-weight-bold">{{ key }}:</th>
                                    <td>{{ value }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-sheet>
                </div>
                <div class="d-flex flex-column ga-8">
                    <p
                        class="font-weight-bold"
                        :class="{ 'text-center': $vuetify.display.smAndDown }"
                    >
                        Od uživatele:
                    </p>
                    <v-table
                        class="w-50"
                        :class="{ 'w-100': $vuetify.display.smAndDown }"
                    >
                        <thead>
                            <tr align="left">
                                <th class="font-weight-bold">ID:</th>
                                <th class="font-weight-bold">Jméno:</th>
                                <th class="font-weight-bold">Příjmení:</th>
                                <th class="font-weight-bold">Email:</th>
                                <th class="font-weight-bold">Role:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ activity.causer.id }}</td>
                                <td>{{ activity.causer.firstname }}</td>
                                <td>{{ activity.causer.lastname }}</td>
                                <td>{{ activity.causer.email }}</td>
                                <td>{{ activity.causer.roles.role }}</td>
                            </tr>
                        </tbody>
                    </v-table>
                    <Link :href="route('adminuser.edit', activity.causer.slug)">
                        <v-btn
                            class="align-self-start bg-green"
                            :class="{ 'w-100': $vuetify.display.smAndDown }"
                        >Zobrazit profil
                        </v-btn>
                    </Link>
                </div>
            </v-sheet>
        </v-container>
    </component>
</template>

<style scoped lang="scss"></style>