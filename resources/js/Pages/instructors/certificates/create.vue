<template>
    <SimpleForm
        :formularioJson="formStructure"
        v-model="form"
        @onCancel="$emit('onCancel')"
        @onSumbit="submit"
    >
        <template v-slot:field.ranges="{ _field }">
            <v-card>
                <v-toolbar density="compact" title="Rangos">
                    <v-spacer></v-spacer>
                    <v-btn color="dark" variant="tonal" @click="addRange">
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                </v-toolbar>
                <div class="my-3">
                    <v-row
                        class=""
                        justify="space-between"
                        v-for="(range, index) in form.ranges"
                        :key="index"
                    >
                        <v-col cols="5" md="4" class="pb-1">
                            <v-text-field
                                label="Inicio"
                                v-model.number="range.range_start"
                                :rules="[
                                    isRequired,
                                    // (v) => {
                                    //     if (v > range.range_end) {
                                    //         return 'El rango inicial debe ser menor al rango final';
                                    //     }
                                    //     return true;
                                    // },
                                ]"
                            />
                        </v-col>

                        <v-col cols="5" md="4" class="pb-1">
                            <v-text-field
                                label="Fin"
                                v-model.number="range.range_end"
                                :rules="[
                                    isRequired,
                                    (v) => {
                                        if (v < range.range_start) {
                                            return 'El rango final debe ser mayor al rango inicial';
                                        }
                                        return true;
                                    },
                                ]"
                            />
                        </v-col>

                        <v-col cols="2" md="2" align="end" class="pb-1">
                            <v-btn
                                append-icon="mdi-delete"
                                color="red"
                                variant="tonal"
                                @click="
                                    form.ranges.length === 1
                                        ? null
                                        : removeRange(index)
                                "
                            >
                                {{ range.range_end - range.range_start + 1 }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </template>
    </SimpleForm>
</template>

<script setup>
import { ref } from "vue";
import SimpleForm from "@/components/SimpleForm.vue";
import { useForm } from "@inertiajs/vue3";
const emit = defineEmits(["onCancel", "onSubmit"]);
import { isRequired } from "@/helpers/validations.js";
const props = defineProps({
    formData: {
        type: Object,
        default: (props) =>
            props.formStructure?.reduce((acc, item) => {
                acc[item.key] = item.default;
                //el password no se debe enviar en el formulario cuando se esta editando
                if (item.key === "password" && props.edit) {
                    delete acc[item.key];
                }
                return acc;
            }, {}),
    },
    formStructure: {
        type: Array,
    },
    edit: {
        type: Boolean,
        default: false,
    },
    url: String,
});

const form = useForm({ ...props.formData });

const addRange = () => {
    form.ranges.push({ range_start: "", range_end: "" });
};

const removeRange = (index) => {
    form.ranges.splice(index, 1);
};

const submit = async () => {
    if (props.edit) {
        form.put(props.url, option);
    } else {
        form.post(props.url, option);
    }
};

const option = {
    onSuccess: (page) => {
        console.log("onSuccess");
        emit("onCancel");
    },
    onError: (errors) => {
        console.log("onError");
    },
    onFinish: (visit) => {
        console.log("onFinish");
    },
};
</script>
