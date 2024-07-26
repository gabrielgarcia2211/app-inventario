<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management-product"
        :style="{ width: '60rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Marcar Salida</h3>
        </template>
        <hr />
        <h6>{{ headerSale }}</h6>
        <Select
            :options="clients"
            v-model="formOutput.client"
            :class="{ 'p-invalid': errors.client }"
            placeholder="Selecciona un cliente"
            optionLabel="name"
            optionValue="name"
            style="width: 100%; margin-top: 12px"
            showClear
        />
        <small v-if="errors.client" class="p-error">{{ errors.client }}</small>
        <div v-if="isAllSize">
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
                        inputId="horizontal-buttons"
                        showButtons
                        buttonLayout="horizontal"
                        :step="1"
                        :min="0"
                        :max="item.initial_quantity"
                        fluid
                    >
                        <template #incrementbuttonicon>
                            <span class="pi pi-plus" />
                        </template>
                        <template #decrementbuttonicon>
                            <span class="pi pi-minus" />
                        </template>
                    </InputNumber>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="size-quantity-form">
                <InputNumber
                    v-model="all.quantity"
                    class="inputtext-product"
                    inputId="horizontal-buttons"
                    showButtons
                    buttonLayout="horizontal"
                    :step="1"
                    :min="0"
                    :max="all.initial_quantity"
                    placeholder="Cantidad"
                    fluid
                >
                    <template #incrementbuttonicon>
                        <span class="pi pi-plus" />
                    </template>
                    <template #decrementbuttonicon>
                        <span class="pi pi-minus" />
                    </template>
                </InputNumber>
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    label="Marcar"
                    severity="success"
                    style="margin-right: 10px"
                    @click="extractProduct"
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
    props: ["outputVisible", "productId"],
    data() {
        return {
            headerSale: "Venta...",
            isAllSize: false,
            visible: this.outputVisible,
            clients: [],
            sizes: null,
            all: {},
            formOutput: {
                client: null,
                currentQuantity: null,
            },
            errors: {},
        };
    },
    components: {},
    watch: {},
    mounted() {},
    created() {
        this.initServices();
        this.getProduct();
    },
    methods: {
        async initServices() {
            this.clients = await this.$getEnumClientName();
        },
        async validateForm() {
            let initialRules = {
                client: Yup.string().required("El cliente es obligatorio"),
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formOutput, {
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
        getProduct() {
            this.$axios
                .get("/products/" + this.productId)
                .then((response) => {
                    const { data } = response.data;
                    this.isAllSize = data?.is_total == "SIZE" ? true : false;
                    if (this.isAllSize) {
                        this.headerSale = "Venta por talla";
                        this.sizes = data.product_sizes;
                    } else {
                        this.headerSale = "Venta total";
                        this.all.id = data.product_sizes[0].id;
                        this.all.size = data.product_sizes[0].size;
                        this.all.quantity = data.product_sizes[0].quantity;
                        this.all.initial_quantity =
                            data.product_sizes[0].initial_quantity;
                    }
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        async extractProduct() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.formOutput.currentQuantity = this.isAllSize
                    ? this.sizes
                    : [this.all];
                this.$axios
                    .post(
                        "/product-size/extract/" + this.productId,
                        this.formOutput
                    )
                    .then((response) => {
                        this.$alertSuccess("Salida procesada");
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
    },
};
</script>

<style></style>
