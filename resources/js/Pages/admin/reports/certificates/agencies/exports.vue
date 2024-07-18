<template>
    <v-form @submit.prevent="getDataExport">
        <v-row>
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
    url: String,
});
const status = ref("all");

const options = ref({
    datas: [],
    fileName: "reporte",
    type: "xlsx",
});

const isApprovedItems = [
    { title: "Todos", value: "all" },
    { title: "Disponible", value: null },
    { title: "Pendiente", value: 0 },
    { title: "Aprobado", value: 1 },
];

const getDataExport = () => {
    router.post(
        props.url + "/export",
        {
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
                            "instructor",
                            "start_date",
                            "end_date",
                        ],
                        sheetHeader: [
                            // "Fecha de creación",
                            "Estado",
                            "Número",
                            "Curso",
                            "Estudiante",
                            "Instructor",
                            "Fecha de inicio",
                            "Fecha de fin",
                        ],
                    },
                ];
                const toExport = new ExportJsonExcel(options.value);
                toExport.saveExcel();
            },
            onError: () => {
                console.log("Error...");
            },
        }
    );
};
</script>
