<template>
    <v-form @submit.prevent="getDataExport">
        <v-row>
            <v-col cols="12">
                <v-select v-model="agency" :items="agencies" label="Agencia" />
            </v-col>
            <v-col cols="12">
                <v-select
                    v-model="status"
                    :items="isApprovedItems"
                    label="Estado"
                    required
                />
            </v-col>

            <v-col cols="12">
                <v-btn block color="primary" type="submit">
                    Exportar a Excel
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/core";

import ExportJsonExcel from "js-export-excel";

const props = defineProps({
    agencies: Array,
    url: String,
});
const status = ref("all");
const agency = ref("all");

/*
{
    "id": 1,
    "created_at": "13/07/2024",
    "number": "10",
    "status": "001",
    "start_date": "2024-07-12",
    "end_date": "2024-08-01",
    "is_approved": 0,
    "course_id": 1,
    "course": "Manejo preventivo de motocicletas",
    "student_id": 1,
    "student": "12345678 Juan Perez Gomez",
    "instructor_id": 1,
    "agency": "Agencia 1"
}
*/
const  options = ref({
    datas: [],
    fileName: "reporte",
    type: "xlsx",
});

const isApprovedItems = [
    { title: "Ambos", value: "all" },
    { title: "Pendiente", value: 0 },
    { title: "Aprobado", value: 1 },
];

const getDataExport = () => {
    router.post(
        props.url + "/export",
        {
            agency: agency.value,
            status: status.value,
        },
        {
            onSuccess: (page) => {

                options.value.datas = [
                    {
                        sheetData: page.props.flash.data,
                        sheetName: "Reporte",
                        sheetFilter: [
                            // "created_at",
                            "is_approved",
                            "number",
                            "course",
                            "student",
                            "start_date",
                            "end_date",
                            "agency",
                        ],
                        sheetHeader: [
                            // "Fecha de creación",
                            "Aprobado",
                            "Número",
                            "Curso",
                            "Estudiante",
                            "Fecha de inicio",
                            "Fecha de fin",
                            "Agencia",
                        ],
                    }
                ]
                const toExport = new ExportJsonExcel(options.value);
                toExport.saveExcel();

            },
            onError: () => {
                console.log("Error...");
            },
        }
    );
    console.log("getDataExport");
};

const init = () => {
    // agency.value = agencies[0].id;
};

init();
</script>
