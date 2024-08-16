<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
           
        </HeadingPage>

        <v-card>
            <DataTable :headers="headers" :items="items" :url="url">
                <template v-slot:header="{ filter }">
                    <div class="pa-3">
                        <v-row justify="end">
                            <v-col cols="6">
                                <v-text-field
                                    v-model="filter.search"
                                    label="Buscar"
                                />
                            </v-col>
                        </v-row>
                    </div>
                </template>


                <template v-slot:item.certificate="{ item }">
                    {{ item.certificate.number }}
                </template>

                <template v-slot:item.user="{ item }">
                    {{ item.user.username }}
                </template>

                <template v-slot:item.is_approved="{ item }">
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-btn
                                v-bind="props"
                                :color="
                                    item.is_approved === 0
                                        ? 'warning'
                                        : item.is_approved === 1
                                        ? 'success'
                                        : 'error'
                                "
                                variant="tonal"
                            >
                                {{
                                    item.is_approved === 0
                                        ? "Pendiente"
                                        : item.is_approved === 1
                                        ? "Aprobado"
                                        : "Rechazado"
                                }}
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item
                                prepend-icon="mdi-plus"
                                title="Aprobar"
                                color="primary"
                                @click="
                                    router.put(
                                        url +
                                            '/' +
                                            item[`${primaryKey}`] +
                                            '/change-state',
                                        {
                                            is_approved: 1,
                                        }
                                    )
                                "
                            />
                            <v-list-item
                                color="red"
                                prepend-icon="mdi-close"
                                title="Rechazar"
                                @click="
                                    router.put(
                                        url +
                                            '/' +
                                            item[`${primaryKey}`] +
                                            '/change-state',
                                        {
                                            is_approved: 2,
                                        }
                                    )
                                "
                            />
                        </v-list>
                    </v-menu>
                </template>

          
            </DataTable>
        </v-card>

    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import DataTable from "@/components/DataTable.vue";
import { router } from "@inertiajs/core";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
    agencies: Array,
    courses: Array,
});

const primaryKey = "id";
const url = "/i/certificates";

const formStructure = [
    {
        key: "course_id",
        label: "Curso",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.courses,
        itemTitle: "name",
        itemValue: "id",
    },

    {
        key: "agency_id",
        label: "Agencia",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.agencies,
        itemTitle: "name",
        itemValue: "id",
    },
    {
        key: "ranges",
        label: "Rangos",
        type: "hidden",
        required: true,
        cols: 12,
        default: [
            {
                range_start: "",
                range_end: "",
            },
        ],
    },
];
</script>
