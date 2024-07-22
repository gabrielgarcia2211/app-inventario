<template>
    <Dialog
        v-model:visible="visible"
        header="Editar Producto"
        modal
        class="dialog-management-product"
        :style="{ width: '60rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Guardar Producto</h3>
        </template>
        <div class="product-form">
            <div class="product-form-column">
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-product"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="product.name"
                        @input="clearError('name')"
                        placeholder="Ingrese el nombre del producto"
                    />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <small v-if="errors.name" class="p-error">{{
                    errors.name
                }}</small>
            </div>
            <div class="product-form-column">
                <FloatLabel>
                    <InputText
                        id="description"
                        class="inputtext-product"
                        :class="{ 'p-invalid': errors.description }"
                        v-model="product.description"
                        @input="clearError('description')"
                        placeholder="Ingrese la descripción del producto"
                    />
                    <label for="description">Descripción</label>
                </FloatLabel>
                <small v-if="errors.description" class="p-error">{{
                    errors.description
                }}</small>
            </div>
            <div class="product-form-column">
                <FloatLabel>
                    <InputNumber
                        id="price"
                        class="inputtext-product"
                        :class="{ 'p-invalid': errors.price }"
                        v-model="product.price"
                        @input="clearError('price')"
                        mode="decimal"
                        placeholder="Ingrese el precio del producto"
                    />
                    <label for="price">Precio</label>
                </FloatLabel>
                <small v-if="errors.price" class="p-error">{{
                    errors.price
                }}</small>
            </div>
        </div>
        <div class="product-form">
            <div class="product-form-column">
                <Select
                    :options="categorys"
                    v-model="product.category"
                    placeholder="Selecciona categoria"
                    :class="{ 'p-invalid': errors.category }"
                    optionLabel="name"
                    optionValue="name"
                    style="width: 100%"
                />
                <small v-if="errors.category" class="p-error">{{
                    errors.category
                }}</small>
            </div>
        </div>
        <div class="product-form">
            <div class="product-form-column">
                <ToggleButton
                    v-model="isAllSize"
                    onLabel="Total"
                    offLabel="Por talla"
                    onIcon="pi pi-check-square"
                    offIcon="pi pi-list"
                    class="w-36"
                    aria-label="Do you confirm"
                />
            </div>
        </div>
        <div v-if="isAllSize">
            <hr />
            <h5>Cantidad por Talla</h5>
            <div class="size-quantity-form">
                <div
                    v-for="(item, index) in sizes"
                    :key="index"
                    class="size-quantity-row"
                >
                    <InputText
                        v-model="item.size"
                        class="inputtext-product"
                        placeholder="Talla"
                        disabled
                    />
                    <InputNumber
                        v-model="item.quantity"
                        class="inputtext-product"
                        mode="decimal"
                        placeholder="Cantidad"
                    />
                </div>
            </div>
        </div>
        <div v-else>
            <hr />
            <h5>Cantidad Total</h5>
            <div class="size-quantity-form">
                <InputNumber
                    v-model="product.quantity"
                    class="inputtext-product"
                    placeholder="Cantidad"
                />
            </div>
        </div>
        <div class="product-form">
            <div class="product-form-column">
                <FileUpload
                    id="attachPhoto"
                    ref="fileUpload"
                    accept="image/*"
                    :multiple="false"
                    :fileLimit="1"
                    :class="{
                        'p-invalid': errors.photo,
                    }"
                    @change="onFileUpload"
                >
                    <template #empty>
                        <p>Adjuntar foto.</p>
                    </template>
                </FileUpload>
                <small
                    v-if="errors.photo"
                    style="display: block"
                    class="p-error"
                    >{{ errors.photo }}</small
                >
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    label="Guardar"
                    severity="success"
                    style="margin-right: 10px"
                    @click="saveProduct"
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
import ToggleButton from "primevue/togglebutton";
import Select from "primevue/select";
import FileUpload from "primevue/fileupload";

export default {
    props: ["dialogVisible"],
    data() {
        return {
            isAllSize: false,
            visible: this.dialogVisible,
            product: {
                name: "",
                description: "",
                price: 0,
                quantity: 0,
                category: "",
                is_total: "ALL",
                photo: null,
            },
            sizes: null,
            categorys: [],
            errors: {},
        };
    },
    components: {
        ToggleButton,
        Select,
        FileUpload,
    },
    watch: {
        isAllSize(newValue) {
            this.product.is_total = !newValue ? "ALL" : "SIZE";
            if (newValue) {
                this.getProductSize();
            }
        },
    },
    created() {
        this.initServices();
    },
    methods: {
        async initServices() {
            this.categorys = await this.$getEnumProductCategory();
        },
        async validateForm() {
            let initialRules = {
                name: Yup.string().required("El nombre es obligatorio"),
                description: Yup.string().required(
                    "La descripcion es obligatoria"
                ),
                price: Yup.string().required("El precio es obligatorio"),
                category: Yup.string().required("La categoria es obligatoria"),
                photo: Yup.string().required("La foto es obligatoria"),
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.product, {
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
        async getProductSize() {
            this.sizes = await this.$getEnumProductSize();
        },
        async saveProduct() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.product.sizes = null;
                if (this.product.is_total == "SIZE")
                    this.product.sizes = this.sizes;
                this.$axios
                    .post("/products/store", this.product, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.$alertSuccess("Producto Añadido");
                        this.$emit("save", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
        },
        onFileUpload() {
            const file_upload = this.$refs.fileUpload;
            if (file_upload) {
                const file = file_upload.files[0];
                if (file) {
                    if (file.type && file.type.startsWith("image/")) {
                        this.product.photo = file;
                    }
                }
            }
        },
        clearError(field) {
            this.errors[field] = "";
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
    },
};
</script>

<style>
.dialog-management-product .p-dialog-close-button {
    display: none !important;
}

.product-form {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 20px;
}

.product-form-column {
    flex: 1;
    min-width: 200px;
}

.inputtext-product {
    width: 100%;
}

.p-invalid {
    border: 1px solid red;
}

.p-error {
    color: red;
    font-size: 0.75em;
    display: block;
}

.size-quantity-form {
    margin-top: 20px;
}

.size-quantity-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.size-quantity-row .p-float-label {
    flex: 1;
}

#attachPhoto [data-pc-name="pcuploadbutton"],
#attachPhoto [data-pc-name="pccancelbutton"] {
    display: none;
}
</style>
