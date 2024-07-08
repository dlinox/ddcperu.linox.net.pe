<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle"></HeadingPage>

        <v-card>
            <DataTable :headers="headers" :items="items" with-action :url="url">
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
                            item.is_approve === 0
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

                <template v-slot:action="{ item }">
                    <div class="d-flex">
                        <BtnDialog title="Asignar Certificado" width="500px">
                            <template v-slot:activator="{ dialog }">
                                <v-btn
                                    color="black"
                                    prepend-icon="mdi-file-certificate-outline"
                                    variant="tonal"
                                    @click="dialog"
                                    class="ms-1"
                                    :disabled="item.is_approved === 1"
                                >
                                    Asignar
                                </v-btn>
                            </template>
                            <template v-slot:content="{ dialog }">
                                <create
                                    :form-structure="formStructure"
                                    @on-cancel="dialog"
                                    :form-data="item"
                                    :url="url"
                                />
                            </template>
                        </BtnDialog>
                    </div>
                </template>
            </DataTable>
        </v-card>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import BtnDialog from "@/components/BtnDialog.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";
import DataTable from "@/components/DataTable.vue";
import { router } from "@inertiajs/core";
import create from "./create.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
    students: Array,
    instructors: Array,
    courses: Array,
});

const primaryKey = "id";
const url = "/s/certificates";

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
        key: "instructor_id",
        label: "Instructor",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.instructors,
        itemTitle: "name",
        itemValue: "id",
    },
    {
        key: "student_id",
        label: "Estudiante",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.students,
        itemTitle: "name",
        itemValue: "id",
    },

    {
        key: "start_date",
        label: "Fecha de inicio",
        type: "date",
        required: true,
        cols: 6,
        default: null,
    },

    {
        key: "end_date",
        label: "Fecha de fin",
        type: "date",
        required: true,
        cols: 6,
        default: null,
    },
    {
        key: "number",
        label: "Número de certificado",
        type: "text",
        required: true,
        cols: 12,
        default: "",
        disabled: true,
        readonly: true,
    },
];
</script>
