<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management-product"
        :style="{ width: '60rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Gestionar Opcion</h3>
        </template>
        <div class="product-form">
            <div class="product-form-column">
                <Select
                    :options="options"
                    v-model="formEnum.parent_id"
                    placeholder="Selecciona opcion"
                    :class="{ 'p-invalid': errors.parent_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    disabled
                />
                <small v-if="errors.parent_id" class="p-error">{{
                    errors.parent_id
                }}</small>
            </div>
        </div>
        <div class="product-form mt-5">
            <div class="product-form-column">
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-product"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formEnum.name"
                        @input="clearError('name')"
                        placeholder="Ingrese el nombre del producto"
                    />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <small v-if="errors.name" class="p-error">{{
                    errors.name
                }}</small>
            </div>
            <div class="product-form-column" v-if="isOption == 'category'">
                <FloatLabel>
                    <InputNumber
                        id="value1"
                        class="inputnumber-product"
                        :class="{ 'p-invalid': errors.value1 }"
                        v-model="formEnum.value1"
                        @input="clearError('value1')"
                        placeholder="Ingrese el porcentaje del producto"
                        showButtons
                        :min="0"
                        :max="100"
                        fluid
                    />
                    <label for="value1">Porcentaje</label>
                </FloatLabel>
                <small v-if="errors.value1" class="p-error">{{
                    errors.value1
                }}</small>
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="!selectedEnum"
                    label="Guardar"
                    severity="success"
                    style="margin-right: 10px"
                    @click="saveEnum"
                />
                <Button
                    v-else
                    label="Actualizar"
                    severity="success"
                    style="margin-right: 10px"
                    @click="updateEnum"
                />
                <Button
                    label="Cancelar"
                    severity="danger"
                    @click="handleDialogClose"
                />
            </div>
        </template>
    </Dialog>
</template>

<script>
import * as Yup from "yup";

export default {
    props: [
        "dialogVisible",
        "selectedEnum",
        "listOptions",
        "selectedOption",
        "isOption",
    ],
    data() {
        return {
            visible: this.dialogVisible,
            formEnum: {
                parent_id: null,
                name: null,
                value1: null,
            },
            errors: {},
            options: this.listOptions,
        };
    },
    components: {},
    watch: {},
    mounted() {
        this.formEnum.parent_id = this.selectedOption;
        if (this.selectedEnum) {
            this.formEnum.name = this.selectedEnum.name;
        }
    },
    created() {},
    methods: {
        async validateForm() {
            let dinamicRules = {};
            let initialRules = {
                parent_id: Yup.string().required("El padre es obligatorio"),
                name: Yup.string().required("El nombre es obligatoria"),
            };
            if (this.isOption == "category") {
                dinamicRules = {
                    value1: Yup.string().required(
                        "El porcentaje es obligatorio"
                    ),
                };
            }
            const schema = Yup.object().shape({
                ...initialRules,
                ...dinamicRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formEnum, {
                    abortEarly: false,
                });
                return true;
            } catch (err) {
                err.inner.forEach((error) => {
                    this.errors[error.path] = error.message;
                });
                return false;
            }
        },
        async saveEnum() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post("/enums/store", this.formEnum)
                    .then((response) => {
                        this.$alertSuccess("Opcion Añadida");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
        },
        async updateEnum() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post(
                        "/enums/update/" + this.selectedEnum.id,
                        this.formEnum
                    )
                    .then((response) => {
                        this.$alertSuccess("Opcion Actualizado");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
    },
};
</script>

<style></style>
