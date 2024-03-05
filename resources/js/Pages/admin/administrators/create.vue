<template>
    <SimpleForm
        :formularioJson="formStructure"
        v-model="form"
        @onCancel="$emit('onCancel')"
        @onSumbit="submit"
    >
        <template v-slot:field.role="{ _field }">
            <v-select
                v-model="form.role"
                :items="_field.options"
                :label="_field.label"
                :itemValue="_field.itemValue"
                :itemTitle="_field.itemTitle"
                :return-object="false"
                :rules="[_field.required ? isRequired : '']"
                hide-details="auto"
                @update:model-value="
                    () => {
                        form.is_sub_admin = false;
                    }
                "
                placeholder="Seleccione un rol"
            ></v-select>
        </template>

        <template v-slot:field.is_sub_admin="{ _field }">
            <v-checkbox
                :disabled="form.role === '002' ? true : false"
                v-model="form.is_sub_admin"
                :label="_field.label"
                :rules="[_field.required ? isRequired : null]"
            ></v-checkbox>
        </template>

        <template
            v-if="form.is_sub_admin === true"
            v-slot:field.agency_id="{ _field }"
        >
            <v-combobox
                v-model="form.agency_id"
                :items="_field.options"
                :label="_field.label"
                :itemValue="_field.itemValue"
                :itemTitle="_field.itemTitle"
                :return-object="false"
                :rules="[_field.required ? isRequired : null]"
                placeholder="Seleccione una agencia"
            ></v-combobox>
        </template>
    </SimpleForm>
</template>

<script setup>
import { ref } from "vue";
import SimpleForm from "@/components/SimpleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { isRequired } from "@/helpers/validations.js";
const emit = defineEmits(["onCancel", "onSubmit"]);

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

const onSelectRole = (value) => {
    if (value === "Administrador") {
        props.formStructure.map((item) => {
            if (item.key === "area_id") {
                item.required = false;
            }
        });
    } else {
        props.formStructure.map((item) => {
            if (item.key === "area_id") {
                item.required = true;
            }
        });
    }
};

const form = useForm({ ...props.formData });

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
