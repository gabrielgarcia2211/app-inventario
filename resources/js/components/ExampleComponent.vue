<template>
    <div class="card">
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
        >
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
                        placeholder="Buscar por rango máximo producto"
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
    },
};
</script>

<style>
.flag {
    vertical-align: middle;
}
.flag-placeholder {
    background-color: #ccc;
}
</style>
