<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle"></HeadingPage>
        <v-card>
            <DataTable
                :headers="headers"
                :items="items"
                :with-action="false"
                :url="url"
            >
                <template v-slot:header="{ filter }">
                    <div class="pa-3">
                        <v-row justify="space-between">
                            <v-col cols="6">
                                <BtnDialog
                                    title="Exportar a Excel"
                                    width="400px"
                                >
                                    <template v-slot:activator="{ dialog }">
                                        <v-btn
                                            @click="dialog"
                                            prepend-icon="mdi-file-excel"
                                            variant="tonal"
                                            color="success"
                                        >
                                            Exportar
                                        </v-btn>
                                    </template>
                                    <template v-slot:content="{ dialog }">
                                        <Exports :agencies="agencies" :url="url" />
                                    </template>
                                </BtnDialog>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                    v-model="filter.search"
                                    label="Buscar"
                                />
                            </v-col>
                        </v-row>
                    </div>
                </template>

                <template v-slot:item.status="{ item }">
                    <v-chip
                        :color="
                            item.status === '000'
                                ? 'blue'
                                : item.status === '001'
                                ? 'orange'
                                : 'green'
                        "
                        label
                    >
                        {{
                            item.status === "000"
                                ? "Disponible"
                                : item.status === "001"
                                ? "Pendiente"
                                : "Asignado"
                        }}
                    </v-chip>
                </template>
                <template v-slot:item.is_approved="{ item }">
                    <template v-if="item.is_approved === null">
                        <v-chip color="blue" label> Disponible </v-chip>
                    </template>
                    <v-chip
                        v-else
                        :color="
                            item.is_approved == 0
                                ? 'orange'
                                : item.is_approved == 1
                                ? 'green'
                                : 'red'
                        "
                        label
                    >
                        {{
                            item.is_approved == 0
                                ? "Pendiente"
                                : item.is_approved == 1
                                ? "Aprobado"
                                : "Rechazado"
                        }}
                    </v-chip>
                </template>
            </DataTable>
        </v-card>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import DataTable from "@/components/DataTable.vue";
import BtnDialog from "@/components/BtnDialog.vue";
import Exports from "./exports.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
    agencies: Array,
});

const url = window.location.pathname;
</script>
