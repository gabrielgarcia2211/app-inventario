<template>
    <div class="card mt-5 container-product">
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
                <div class="flex justify-end">
                    <Button
                        label="Agregar"
                        icon="pi pi-plus"
                        rounded
                        raised
                        @click="addProduct"
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
                            v-for="(sizeInfo, index) in parseSizes(data.sizes)"
                            :key="index"
                            :value="`${sizeInfo.size}: ${$formatNumber(
                                sizeInfo.value
                            )}`"
                            class="size-tag"
                        />
                    </div>
                </template>
            </Column>
            <Column header="Foto">
                <template #body="slotProps">
                    <div style="text-align: center">
                        <img
                            :src="slotProps.data.photo"
                            :alt="'imagen'"
                            class="w-24 rounded cursor-pointer"
                            width="200px"
                            @click="viewImage(slotProps.data.photo)"
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
                        icon="pi pi-trash"
                        class="p-button-rounded p-button-danger"
                        style="margin: 5px"
                        @click="deleteProduct(slotProps.data)"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
    <!-- visualizacion de imagen -->
    <Dialog
        v-model:visible="imgVisible"
        style="width: 50vw"
        :modal="true"
        @hide="imgVisible = false"
    >
        <img
            :src="selectedImage"
            class="w-full"
            style="max-width: 100%; max-height: 70vh; object-fit: contain"
        />
    </Dialog>
    <!-- gestion de producto -->
    <ManagementProductComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        @hidden="hiddenManagementProduct"
        @save="fetchProducts"
    />
</template>

<script>
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementProductComponent from "./management/ManagementProductComponent.vue";
import Tag from "primevue/tag";

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
            imgVisible: false,
            selectedImage: null,
            dialogVisible: false,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagementProductComponent,
        Tag,
    },
    created() {
        this.initFilters();
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
            this.$axios
                .get("/products/", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
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
        addProduct() {
            this.dialogVisible = true;
        },
        editProduct(product) {
            // Lógica para editar un producto
            console.log("Editar producto:", product);
        },
        deleteProduct(product) {
            // Lógica para eliminar un producto
            console.log("Eliminar producto:", product);
        },
        viewImage(imageUrl) {
            this.selectedImage = imageUrl;
            this.imgVisible = true;
        },
        hiddenManagementProduct(status) {
            this.dialogVisible = status;
        },
        parseSizes(sizeString) {
            if (!sizeString) return [];
            return sizeString.split(";").map((sizePair) => {
                const [size, value] = sizePair.split(":");
                return { size, value };
            });
        },
    },
};
</script>

<style>
.container-product {
    margin-right: 40px;
    margin-left: 40px;
}

.p-tag {
    color: white !important;
    background-color: #038692 !important;
}

.size-tags {
    display: flex;
    flex-wrap: wrap;
}

.size-tag {
    padding: 2px;
    margin: 3px;
}
</style>
