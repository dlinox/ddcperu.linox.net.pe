<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle"> </HeadingPage>
        <v-container fluid v-if="user">
            <v-row
                v-if="user.role === 'Administrador' && user.agency_id === null"
            >
                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Instructores </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5"> {{ instructors }} </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Instructores
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Certificados </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-certificate-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ certificates }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Certificados
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Sub Agencias </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3"
                                >mdi-office-building-outline</v-icon
                            >
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ subagencies }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Sub Agencias
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Cursos </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-book-education-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ courses }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Cursos
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>

            <v-row
                v-if="user.agency_id !== null && user.role === 'Administrador'"
            >
                <v-col cols="12" sm="6" md="4">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Instructores </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5"> {{ instructors }} </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Instructores
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Certificados </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-certificate-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ certificates }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Certificados
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Cursos </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-book-education-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ courses }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Cursos
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>

            <v-row  v-if="user.role !== 'Administrador'">
                <v-col cols="12" sm="6">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Cursos </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-book-education-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ courses }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Cursos
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Certificados </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">
                                mdi-certificate-outline
                            </v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">
                                    {{ certificates }}
                                </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Certificados
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AdminLayout>
</template>
<script setup>
import { computed } from "vue";
import { router, usePage, Head } from "@inertiajs/vue3";
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import BtnDialog from "@/components/BtnDialog.vue";

const user = computed(() => usePage().props?.user);

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,

    instructors: [String, Number],
    certificates: [String, Number],
    subagencies: [String, Number],
    courses: [String, Number],
});
</script>
