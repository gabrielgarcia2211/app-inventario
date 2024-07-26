<template>
    <div class="card mt-5 mb-5 container-product">
        <DataTable
            v-model:filters="filters"
            :loading="loading"
            :value="products"
            :paginator="true"
            :rows="perPage"
            :sortField="sortField"
            :sortOrder="sortOrder"
            :totalRecords="totalRecords"
            :lazy="true"
            @page="onPage"
            @sort="onSort"
            @filter="onFilters"
            filterDisplay="menu"
            removableSort
            stripedRows
            scrollable
            showGridlines
        >
            <template #header>
                <div class="header-container-product mb-2">
                    <Button
                        label="Agregar"
                        icon="pi pi-plus"
                        rounded
                        raised
                        @click="addProduct"
                    />
                    <Select
                        v-model="selectedCategory"
                        :options="listCategorys"
                        :optionLabel="optionLabelCategory"
                        placeholder="Selecciona la categoria"
                        class="w-full md:w-56"
                        style="width: 20%"
                        showClear
                        @change="changeSelectCategory"
                    />
                </div>
            </template>
            <Column
                field="name"
                header="Nombre"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ data.name }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText
                        v-model="filterModel.value"
                        type="text"
                        class="p-column-filter"
                        placeholder="Buscar por nombre"
                    />
                </template>
            </Column>
            <Column
                field="description"
                header="Descripcion"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ data.description }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText
                        v-model="filterModel.value"
                        type="text"
                        class="p-column-filter"
                        placeholder="Buscar por producto"
                    />
                </template>
            </Column>
            <Column
                field="price"
                header="Precio"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ $formatPrice(data.price) }}
                </template>
                <template #filter="{ filterModel }">
                    <InputNumber
                        v-model="filterModel.value"
                        type="text"
                        class="p-column-filter"
                        placeholder="Buscar por precio"
                    />
                </template>
            </Column>
            <Column
                v-if="visibleAjustedPrice"
                field="adjusted_price"
                header="Precio por categoria"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ $formatPrice(data.adjusted_price) }}
                </template>
            </Column>
            <Column
                field="category"
                header="Categoria"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ data.category }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText
                        v-model="filterModel.value"
                        type="text"
                        class="p-column-filter"
                        placeholder="Buscar por categoria"
                    />
                </template>
            </Column>
            <Column
                field="size"
                header="Talla / Cantidad"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    <div class="size-tags">
                        <Tag
                            v-for="(sizeInfo, index) in $parseSizes(data.sizes)"
                            :key="index"
                            :value="`${sizeInfo.size}: ${$formatNumber(
                                sizeInfo.quantity
                            )}`"
                            class="size-tag"
                        />
                    </div>
                </template>
            </Column>
            <Column header="Foto">
                <template #body="{ data }">
                    <div style="text-align: center">
                        <Image
                            :src="data.photo"
                            :alt="'imagen'"
                            class="w-24 rounded cursor-pointer"
                            width="200px"
                            preview
                        />
                    </div>
                </template>
            </Column>
            <Column
                header="Acciones"
                style="min-width: 120px; text-align: center"
            >
                <template #body="slotProps">
                    <Button
                        icon="pi pi-pencil"
                        class="p-button-rounded p-button-success"
                        style="margin: 5px"
                        @click="editProduct(slotProps.data)"
                    />
                    <Button
                        icon="pi pi-reply"
                        class="p-button-rounded p-button-warn"
                        style="margin: 5px"
                        @click="outputProduct(slotProps.data.id)"
                    />
                    <Button
                        icon="pi pi-trash"
                        class="p-button-rounded p-button-danger"
                        style="margin: 5px"
                        @click="deleteProduct(slotProps.data.id)"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
    <!-- gestion de producto -->
    <ManagementProductComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedProduct="selectedProduct"
        @hidden="hiddenManagementProduct"
        @reload="reloadProducts"
    />
    <!-- salid de producto -->
    <OutputProductComponent
        v-if="outputVisible"
        :outputVisible="outputVisible"
        :productId="productId"
        @hidden="hiddenOutputProduct"
        @reload="reloadProducts"
    />
</template>

<script>
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementProductComponent from "./management/ManagementProductComponent.vue";
import OutputProductComponent from "./management/OutputProductComponent.vue";

export default {
    data() {
        return {
            products: [],
            perPage: 5,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            dialogVisible: false,
            outputVisible: false,
            selectedProduct: null,
            listCategorys: [],
            filterSelect: {
                category: null,
            },
            selectedCategory: null,
            visibleAjustedPrice: false,
            productId: null,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagementProductComponent,
        OutputProductComponent,
    },
    created() {
        this.initFilters();
        this.initServices();
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        initFilters() {
            this.filters = {
                description: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                price: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                category: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
            };
        },
        async initServices() {
            this.listCategorys = await this.$getEnumProductCategory();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchProducts();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchProducts();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableProducts(key),
                                constraint.matchMode,
                                constraint.value,
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
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
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
        reloadProducts() {
            this.fetchProducts();
            this.selectedProduct = this.productId = null;
            this.dialogVisible = this.outputVisible = false;
        },
        addProduct() {
            this.selectedProduct = null;
            this.dialogVisible = true;
        },
        editProduct(product) {
            this.selectedProduct = product;
            this.dialogVisible = true;
        },
        async deleteProduct(productId) {
            const result = await this.$swal.fire({
                title: "¿Estás seguro?",
                text: "Estás a punto de eliminar esta imagen. ¿Estás seguro de que deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/products/${productId}`);
                    this.products = this.products.filter(
                        (product) => product.id !== productId
                    );
                    this.$alertSuccess("Producto eliminado con éxito");
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        hiddenManagementProduct(status) {
            this.dialogVisible = status;
        },
        hiddenOutputProduct(status) {
            this.outputVisible = status;
        },
        changeSelectCategory(event) {
            this.filterSelect.category = event.value?.name;
            this.visibleAjustedPrice = event.value != null ? true : false;
            this.fetchProducts();
        },
        optionLabelCategory(option) {
            return `${option.name} | ${option.percentage * 100}%`;
        },
        outputProduct(productId) {
            this.outputVisible = true;
            this.productId = productId;
        },
    },
};
</script>

<style>
.container-product {
    margin-right: 40px;
    margin-left: 40px;
}

.header-container-product {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>
