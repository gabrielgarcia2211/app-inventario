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
            <Column
                field="client"
                header="Nombre cliente"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ data.client }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText
                        v-model="filterModel.value"
                        type="text"
                        class="p-column-filter"
                        placeholder="Buscar por cliente"
                    />
                </template>
            </Column>
            <Column
                field="name"
                header="Nombre producto"
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
                field="outflows"
                header="Talla / Cantidad"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    <div class="size-tags">
                        <Tag
                            v-for="(sizeInfo, index) in $parseSizes(
                                data.outflows
                            )"
                            :key="index"
                            :value="`${sizeInfo.size}: ${$formatNumber(
                                sizeInfo.quantity
                            )}`"
                            class="size-tag"
                        />
                    </div>
                </template>
            </Column>
            <Column
                field="fecha_salida"
                header="Fecha de salida"
                sortable
                :showClearButton="false"
                style="min-width: 200px"
            >
                <template #body="{ data }">
                    {{ data.fecha_salida }}
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
        </DataTable>
    </div>
</template>

<script>
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

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
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchProductOutflow();
    },
    methods: {
        initFilters() {
            this.filters = {
                name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                client: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                fecha_salida: {
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
            this.fetchProductOutflow();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchProductOutflow();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableProductOutflow(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchProductOutflow();
        },
        fetchProductOutflow() {
            this.loading = true;
            this.$axios
                .get("/outflows/list", {
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
    },
};
</script>

<style></style>
