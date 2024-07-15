<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
            <template #actions>
                <BtnDialog title="Registrar" width="800px">
                    <template v-slot:activator="{ dialog }">
                        <v-btn
                            @click="dialog"
                            prepend-icon="mdi-plus"
                            variant="flat"
                        >
                            Nuevo
                        </v-btn>
                    </template>
                    <template v-slot:content="{ dialog }">
                        <create
                            :form-structure="formStructure"
                            @on-cancel="dialog"
                            :url="url"
                        />
                    </template>
                </BtnDialog>
            </template>
        </HeadingPage>

        <v-card flat rounded="0">
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

                <template v-slot:item.document_type="{ item }">
                    {{
                        item.document_type === "001"
                            ? "DNI"
                            : item.document_type === "002"
                            ? "Carnet de extranjería"
                            : "Pasaporte"
                    }}
                </template>

                <template v-slot:item.link="{ item }">
                    <v-btn
                        icon="mdi-link-variant"
                        color="black"
                        density="comfortable"
                        variant="outlined"
                        :href="item.link"
                        target="_blank"
                        :disabled="!item.link"
                    ></v-btn>
                </template>

                <template v-slot:action="{ item }">
                    <BtnDialog title="Editar" width="800px">
                        <template v-slot:activator="{ dialog }">
                            <v-btn
                                color="info"
                                icon
                                variant="tonal"
                                density="comfortable"
                                @click="dialog"
                            >
                                <v-icon size="18" icon="mdi-pencil"></v-icon>
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
                                :form-data="item"
                                :edit="true"
                                :url="url + '/' + item[`${primaryKey}`]"
                            />
                        </template>
                    </BtnDialog>

                    <v-btn
                        icon
                        variant="tonal"
                        density="comfortable"
                        class="ml-1"
                        color="red"
                    >
                        <DialogConfirm
                            @onConfirm="
                                () =>
                                    router.delete(
                                        url + '/' + item[`${primaryKey}`]
                                    )
                            "
                        />
                        <v-icon size="18" icon="mdi-delete-empty"></v-icon>
                    </v-btn>
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
    agencies: Array,
});

const primaryKey = "id";
const url = "/a/students";

const formStructure = [
    {
        key: "document_type",
        label: "Tipo de documento",
        type: "select",
        required: true,
        cols: 12,
        colMd: 6,
        default: "001",
        options: [
            { value: "001", title: "DNI" },
            { value: "002", title: "Carnet de extranjería" },
            { value: "003", title: "Pasaporte" },
        ],
        itemValue: "id",
        itemTitle: "title",
    },
    {
        key: "document_number",
        label: "Número de documento",
        type: "text",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "name",
        label: "Nombre",
        type: "text",
        required: true,
        cols: 12,
        default: "",
    },
    {
        key: "paternal_surname",
        label: "Apellido paterno",
        type: "text",
        required: false,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "maternal_surname",
        label: "Apellido materno",
        type: "text",
        required: false,
        cols: 12,
        colMd: 6,
        default: "",
    },

    {
        key: "email",
        label: "Correo",
        type: "email",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },

    {
        key: "phone_number",
        label: "Teléfono",
        type: "text",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "link",
        label: "Enlace",
        type: "text",
        required: false,
        cols: 12,
        default: "",
    },
    {
        key: "agency_id",
        label: "Agencia",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.agencies,
        itemValue: "id",
        itemTitle: "name",
    },
];
</script>
