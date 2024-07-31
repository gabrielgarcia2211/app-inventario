<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management-product"
        :style="{ width: '60rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Marcar Entrada</h3>
        </template>

        <div class="dialog-content">
            <h6>{{ headerSale }}</h6>

            <div class="select-client">
                <Select
                    :options="seamstres"
                    v-model="formInput.seamstre_id"
                    :class="{ 'p-invalid': errors.seamstre_id }"
                    placeholder="Selecciona un modista"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%; margin-top: 12px"
                    showClear
                    filter
                />
                <small v-if="errors.seamstre_id" class="p-error">{{
                    errors.seamstre_id
                }}</small>
            </div>
            <div class="data-table-container">
                <DataTable
                    v-model:filters="filters"
                    v-model:selection="formInput.product"
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
                    @click="inputGeneralProduct"
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
    props: ["inputGeneralVisible"],
    data() {
        return {
            headerSale: "Entrada general",
            isAllSize: false,
            visible: this.inputGeneralVisible,
            seamstres: [],
            sizes: null,
            all: {},
            formInput: {
                seamstre_id: null,
                product: null,
                currentQuantity: null,
                type: "input",
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
        "formInput.product"(value) {
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
            const comboNames = ["seamstres"];
            const response = await this.$getEnumsOptions(comboNames);
            const { seamstres: responsSeamstres } = response.data;
            this.seamstres = responsSeamstres;
        },
        async validateForm() {
            let initialRules = {
                seamstre_id: Yup.string().required("El modista es obligatorio"),
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
                await schema.validate(this.formInput, {
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
                        this.sizes = data.product_sizes.map((size) => {
                            size.quantity = 0;
                            return size;
                        });
                    } else {
                        this.all.id = data.product_sizes[0].id;
                        this.all.size = data.product_sizes[0].size;
                        this.all.quantity = 0;
                    }
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        async inputGeneralProduct() {
            this.formInput.currentQuantity =
                this.isAllSize == "SIZE" ? this.sizes : [this.all];
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post(
                        "/product-size/enter/" + this.productId,
                        this.formInput
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
