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

        <div class="dialog-content">
            <h6>{{ headerSale }}</h6>

            <div class="select-client">
                <Select
                    :options="clients"
                    v-model="formOutput.client"
                    :class="{ 'p-invalid': errors.client }"
                    placeholder="Selecciona un cliente"
                    optionLabel="name"
                    optionValue="name"
                    style="width: 100%; margin-top: 12px"
                    showClear
                    filter
                />
                <small v-if="errors.client" class="p-error">{{
                    errors.client
                }}</small>
            </div>

            <div class="data-table-container">
                <DataTable
                    v-model:filters="filters"
                    v-model:selection="formOutput.product"
                    :loading="loading"
                    :value="products"
                    :paginator="true"
                    :rows="perPage"
                    :totalRecords="totalRecords"
                    :lazy="true"
                    @page="onPage"
                    @filter="onFilters"
                    filterDisplay="row"
                    stripedRows
                    showGridlines
                    style="margin-top: 20px; margin-bottom: 10px"
                >
                    <Column
                        header="Marcar"
                        selectionMode="single"
                        headerStyle="width: 3rem"
                    ></Column>
                    <Column
                        field="name"
                        header="Nombre del producto"
                        style="min-width: 12rem"
                    >
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                @input="filterCallback()"
                                placeholder="Buscar por producto"
                            />
                        </template>
                    </Column>
                </DataTable>
                <small v-if="errors.product" class="p-error">{{
                    errors.product
                }}</small>
                <h6 v-if="isAllSize">Cantidad disponible</h6>
                <div v-if="isAllSize == 'SIZE'">
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
                <div v-if="isAllSize == 'ALL'">
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
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    label="Marcar"
                    severity="success"
                    style="margin-right: 10px"
                    @click="extractGeneralProduct"
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
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

export default {
    props: ["generalVisible"],
    data() {
        return {
            headerSale: "Venta general",
            isAllSize: false,
            visible: this.generalVisible,
            clients: [],
            sizes: null,
            all: {},
            formOutput: {
                client: null,
                product: null,
                currentQuantity: null,
            },
            errors: {},
            // variables de datatable
            products: [],
            perPage: 5,
            totalRecords: 0,
            page: 1,
            filters: null,
            filtroInfo: [],
            loading: true,
            productId: null,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
    },
    watch: {
        "formOutput.product"(value) {
            if (!value) return;
            this.productId = value.id;
            this.getProduct(value.id);
        },
    },
    created() {
        this.initServices();
        this.initFilters();
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        async initServices() {
            this.clients = await this.$getEnumClientName();
        },
        async validateForm() {
            let initialRules = {
                client: Yup.string().required("El cliente es obligatorio"),
                product: Yup.object()
                    .shape({
                        id: Yup.string().required("El producto es obligatorio"),
                    })
                    .required("El producto es obligatorio"),
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
        getProduct(productId) {
            this.$axios
                .get("/products/" + productId)
                .then((response) => {
                    const { data } = response.data;
                    this.isAllSize = data?.is_total;
                    if (this.isAllSize == "SIZE") {
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
        async extractGeneralProduct() {
            this.formOutput.currentQuantity =
                this.isAllSize == "SIZE" ? this.sizes : [this.all];
            const isValidQuantity = this.formOutput.currentQuantity.some(
                (value) => {
                    return value.initial_quantity > 0;
                }
            );
            if (
                !isValidQuantity ||
                this.formOutput.currentQuantity.length == 0
            ) {
                this.$alertWarning(
                    "No hay stock de ninguna talla para el producto."
                );
                return;
            }
            const isValid = await this.validateForm();
            if (isValid) {
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
        initFilters() {
            this.filters = {
                name: {
                    value: null,
                    constraints: [{ matchMode: FilterMatchMode.STARTS_WITH }],
                },
            };
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchProducts();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (filter.value) {
                            this.filtroInfo.push([
                                this.$relationTableProducts(key),
                                constraint.matchMode,
                                filter.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchProducts();
        },
        fetchProducts() {
            this.loading = true;
            this.$axios
                .get("/products/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        filters: this.filtroInfo,
                    },
                })
                .then((response) => {
                    this.products = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
    },
};
</script>

<style>
.dialog-content {
    padding: 1rem;
}

.select-client {
    margin-bottom: 1rem;
}

.data-table-container {
    margin-top: 1rem;
}
</style>
