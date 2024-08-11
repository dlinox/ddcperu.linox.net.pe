<template>
    <SimpleForm
        :formularioJson="formStructure"
        v-model="form"
        @onCancel="$emit('onCancel')"
        @onSumbit="submit"
    >
        <template v-slot:field.certificate_id="{ _field }">
            <v-combobox
                v-model="form.certificate_id"
                :items="numbers"
                label="Número de certificado"
                itemValue="id"
                itemTitle="number"
                :return-object="false"
                :rules="[isRequired]"
            ></v-combobox>
        </template>

        <template v-slot:field.instructor_id="{ _field }">
            <v-alert
                v-if="!instructorsOptions.length"
                type="warning"
                variant="tonal"
                class="mb-3"
                density="compact"
            >
                No hay instructores disponibles, para este curso.
            </v-alert>
            <v-combobox
                v-model="form.instructor_id"
                :items="instructorsOptions"
                :label="_field.label"
                :itemValue="_field.itemValue"
                :itemTitle="_field.itemTitle"
                :return-object="false"
                :rules="[isRequired]"
            ></v-combobox>
        </template>
    </SimpleForm>
</template>

<script setup>
import SimpleForm from "@/components/SimpleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
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

    instructors: Array,
    numbers: Array,
});

const form = useForm({ ...props.formData });

const instructorsOptions = ref([]);

const onSelectRole = (value) => {
    console.log(value);
    instructorsOptions.value = props.instructors.filter(
        (instructor) => instructor.course_id === value
    );
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

const init = () => {
    onSelectRole(form.course_id);
};

init();
</script>
