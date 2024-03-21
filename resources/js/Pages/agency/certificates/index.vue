<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle"> </HeadingPage>

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

                <template v-slot:item.course="{ item }">
                    [{{ item.range.min }} - {{ item.range.max }}]
                    {{ item.course.name }}
                </template>

                <template v-slot:action="{ item }">
                    <div class="d-flex">
                        <BtnDialog title="Certificados" width="500px">
                            <template v-slot:activator="{ dialog }">
                                <v-btn
                                    color="black"
                                    icon="mdi-plus"
                                    variant="tonal"
                                    density="comfortable"
                                    @click="dialog"
                                >
                                </v-btn>
                            </template>
                            <template v-slot:content="{ dialog }">
                                <v-row>
                                    <v-col
                                        cols="12"
                                        v-for="certificate in item.certificateStudent"
                                        class="border-b py-1"
                                    >
                                        <v-list-item>
                                            <v-list-item-title>
                                                N°:
                                                {{ certificate.number }} -
                                                <v-chip
                                                    density="comfortable"
                                                    label
                                                    class="me-1"
                                                    :color="
                                                        certificate.status ===
                                                        '000'
                                                            ? 'blue'
                                                            : certificate.status ==
                                                              '001'
                                                            ? 'yellow'
                                                            : 'green'
                                                    "
                                                >
                                                    {{
                                                        certificate.status ===
                                                        "000"
                                                            ? "Disponible"
                                                            : certificate.status ==
                                                              "001"
                                                            ? "Pendiente"
                                                            : "Asignado"
                                                    }}
                                                </v-chip>

                                                <v-chip
                                                    density="comfortable"
                                                    label
                                                    v-if="
                                                        certificate.is_approved !==
                                                        null
                                                    "
                                                    :color="
                                                        certificate.is_approved ===
                                                        0
                                                            ? 'yellow'
                                                            : certificate.is_approved ==
                                                              1
                                                            ? 'blue'
                                                            : 'red'
                                                    "
                                                >
                                                    {{
                                                        certificate.is_approved ===
                                                        0
                                                            ? "Pendiente"
                                                            : certificate.is_approved ==
                                                              1
                                                            ? "Verificado"
                                                            : "Rechazado"
                                                    }}
                                                </v-chip>
                                            </v-list-item-title>

                                            <v-list-item-subtitle
                                                v-if="certificate.student"
                                            >
                                                Estudinate:
                                                <strong>
                                                    {{ certificate.student }}
                                                </strong>
                                            </v-list-item-subtitle>

                                            <v-list-item-subtitle
                                                v-if="certificate.student"
                                            >
                                                Instructor:
                                                <strong>
                                                    {{ certificate.instructor }}
                                                </strong>
                                            </v-list-item-subtitle>
                                        </v-list-item>
                                    </v-col>
                                </v-row>
                            </template>
                        </BtnDialog>

                        <BtnDialog title="Registrar Certificado" width="500px">
                            <template v-slot:activator="{ dialog }">
                                <v-btn
                                    color="info"
                                    prepend-icon="mdi-file-certificate-outline"
                                    variant="tonal"
                                    @click="dialog"
                                    class="ms-1"
                                >
                                    Registrar
                                </v-btn>
                            </template>
                            <template v-slot:content="{ dialog }">
                                <create
                                    :form-structure="
                                        formStructure.filter(
                                            (field) => field.key !== 'password'
                                        )
                                    "
                                    @on-cancel="dialog"
                                    :form-data="{
                                        instructor_id: null,
                                        student_id: null,
                                        certificate_id: null,
                                        start_date: null,
                                        end_date: null,
                                    }"
                                    :numbers="item.certificateDetails"
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
});

const primaryKey = "id";
const url = "/s/certificates";

const formStructure = [
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
        key: "certificate_id",
        label: "Número de certificado",
        type: "hidden",
        required: true,
        cols: 12,
        default: null,
    },
    //FECHA DE VALIDES
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
];
</script>
