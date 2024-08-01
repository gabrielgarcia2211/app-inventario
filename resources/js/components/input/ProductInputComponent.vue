<template>
    <Card class="container-product">
        <template #title>Entradas</template>
        <template #content>
            <DataTable
                v-model:filters="filters"
                :loading="loading"
                rowGroupMode="rowspan"
                groupRowsBy="seamstress"
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
                    field="seamstress"
                    header="Nombre modista"
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.seamstress }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Buscar por modista"
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
                                :value="`${$formatTextSize(
                                    sizeInfo.size
                                )}: ${$formatNumber(sizeInfo.quantity)}`"
                                class="size-tag"
                            />
                        </div>
                        <Tag
                            :value="`Total: ${$formatNumber(
                                data.total_quantity
                            )}`"
                            class="size-tag-total"
                        />
                    </template>
                </Column>
                <Column
                    field="date_entry"
                    header="Fecha de salida"
                    sortable
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.date_entry }}
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
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
        this.fetchProductInput();
    },
    methods: {
        initFilters() {
            this.filters = {
                seamstress: {
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
            };
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchProductInput();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchProductInput();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableProductInput(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchProductInput();
        },
        fetchProductInput() {
            this.loading = true;
            this.$axios
                .get("/product-inputs/list", {
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
