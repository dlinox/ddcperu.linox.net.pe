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
                    {{ item.course.name }}
                </template>

                <template v-slot:action="{ item }">
                    <BtnDialog title="Registrar Certificado" width="500px">
                        <template v-slot:activator="{ dialog }">
                            <v-btn
                                color="info"
                                prepend-icon="mdi-file-certificate-outline"
                                variant="tonal"
                                @click="dialog"
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
