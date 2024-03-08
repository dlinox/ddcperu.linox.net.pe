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
                            Nueva
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

                <template v-slot:item.agency="{ item }">
                    {{ item.agency.name }}
                </template>

                <template v-slot:item.course="{ item }">
                    {{ item.course.name }}
                </template>

                <template v-slot:item.user="{ item }">
                    {{ item.user.username }}
                </template>

                <template v-slot:item.is_enabled="{ item }">
                    <v-btn
                        :color="item.is_enabled ? 'blue' : 'red'"
                        variant="tonal"
                    >
                        <DialogConfirm
                            text="¿Activar/Desactivar?"
                            @onConfirm="
                                () =>
                                    router.patch(
                                        url +
                                            '/' +
                                            item[`${primaryKey}`] +
                                            '/change-state'
                                    )
                            "
                        />
                        {{ item.is_enabled ? "Activo" : "Inactivo" }}
                    </v-btn>
                </template>

                <template v-slot:action="{ item }">
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
                                                    certificate.status === '000'
                                                        ? 'blue'
                                                        : certificate.status ==
                                                          '001'
                                                        ? 'yellow'
                                                        : 'green'
                                                "
                                            >
                                                {{
                                                    certificate.status === "000"
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

                    <BtnDialog title="Editar" width="500px">
                        <template v-slot:activator="{ dialog }">
                            <v-btn
                                color="info"
                                icon
                                variant="tonal"
                                density="comfortable"
                                @click="dialog"
                                class="ml-1"
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
    courses: Array,
});

const primaryKey = "id";
const url = "/a/certificates";

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
