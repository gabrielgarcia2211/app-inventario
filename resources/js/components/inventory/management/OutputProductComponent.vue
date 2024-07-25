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
        <div v-if="isAllSize">
            <hr />
            <h5>Venta por talla</h5>
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
            <hr />
            <h5>Venta por total</h5>
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
export default {
    props: ["outputVisible", "productId"],
    data() {
        return {
            isAllSize: false,
            visible: this.outputVisible,
            sizes: null,
            all: {},
        };
    },
    components: {},
    watch: {},
    mounted() {},
    created() {
        this.getProduct();
    },
    methods: {
        getProduct() {
            this.$axios
                .get("/products/" + this.productId)
                .then((response) => {
                    const { data } = response.data;
                    this.isAllSize = data?.is_total == "SIZE" ? true : false;
                    if (this.isAllSize) {
                        this.sizes = data.product_sizes;
                    } else {
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
        extractProduct() {
            const currentQuantity = this.isAllSize ? this.sizes : [this.all];
            this.$axios
                .post("/product-size/extract", currentQuantity)
                .then((response) => {
                    this.$alertSuccess("Salida procesada");
                    this.$emit("reload", true);
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
    },
};
</script>

<style></style>
