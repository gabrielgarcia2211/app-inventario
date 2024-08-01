<template>
    <Card class="container-product">
        <template #title>Opciones</template>
        <template #content>
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
                editMode="row"
            >
                <template #header>
                    <div class="header-container-product mb-2">
                        <div class="header-start">
                            <Button
                             v-if="selectedOption"
                                label="Agregar"
                                icon="pi pi-plus"
                                rounded
                                raised
                                @click="addEnum"
                                style="margin-right: 10px"
                            />
                            <Button
                                label="Limpiar filtros"
                                icon="pi pi-filter-slash"
                                class="p-button-danger"
                                rounded
                                raised
                                @click="clearFilters"
                            />
                        </div>
                        <div class="header-end">
                            <Select
                                v-model="selectedOption"
                                :options="listOptions"
                                placeholder="Selecciona la opcion"
                                optionLabel="name"
                                optionValue="id"
                                class="w-full md:w-56"
                                style="width: 100%"
                                @change="changeSelectOption"
                                showClear
                            />
                        </div>
                    </div>
                </template>
                <Column
                    field="parent.name"
                    header="Opcion"
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.parent.name }}
                    </template>
                </Column>
                <Column
                    field="name"
                    header="Nombre"
                    :showClearButton="false"
                    style="min-width: 200px"
                    sortable
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
                    v-if="isOption == 'category'"
                    field="value1"
                    header="Porcentaje"
                    :showClearButton="false"
                    style="min-width: 200px"
                    sortable
                >
                    <template #body="{ data }">
                        {{ data.value1 }}
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
                    header="Acciones"
                    style="min-width: 120px; text-align: center"
                >
                    <template #body="slotProps">
                        <Button
                            v-if="selectedOption"
                            icon="pi pi-pencil"
                            class="p-button-rounded p-button-success"
                            style="margin: 5px"
                            @click="editEnum(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            class="p-button-rounded p-button-danger"
                            style="margin: 5px"
                            @click="deleteEnum(slotProps.data.id)"
                        />
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de opcion -->
    <ManagemenEnumComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedEnum="selectedEnum"
        :listOptions="listOptions"
        :selectedOption="selectedOption"
        :isOption="isOption"
        @hidden="hiddenManagementEnum"
        @reload="reloadEnums"
    />
</template>

<script>
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenEnumComponent from "./management/ManagemenEnumComponent.vue";

export default {
    data() {
        return {
            products: [],
            perPage: 20,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            filterSelect: {
                parent_id: null,
            },
            selectedOption: null,
            listOptions: [],
            selectedEnum: null,
            dialogVisible: false,
            isOption: null,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagemenEnumComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchEnumOptios();
        this.initServices();
    },
    methods: {
        initServices() {
            this.$axios
                .get("/enums/fathers")
                .then((response) => {
                    const { data } = response.data;
                    this.listOptions = data;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        initFilters() {
            this.filters = {
                name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                value1: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
            };
        },
        clearFilters() {
            this.initFilters();
            this.filtroInfo = [];
            this.fetchEnumOptios();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchEnumOptios();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchEnumOptios();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableEnumOption(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchEnumOptios();
        },
        fetchEnumOptios() {
            this.loading = true;
            this.$axios
                .get("/enums/list", {
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
        addEnum() {
            this.selectedEnum = null;
            this.dialogVisible = true;
        },
        editEnum(product) {
            this.selectedEnum = product;
            this.dialogVisible = true;
        },
        async deleteEnum(enumId) {
            const result = await this.$swal.fire({
                title: "¿Estás seguro?",
                text: "Estás a punto de eliminar esta opcion. ¿Estás seguro de que deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/enums/${enumId}`);
                    this.$alertSuccess("Producto eliminado con éxito");
                    this.fetchEnumOptios();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reloadEnums() {
            this.fetchEnumOptios();
            this.selectedEnum = null;
            this.dialogVisible = false;
        },
        hiddenManagementEnum(status) {
            this.dialogVisible = status;
        },
        changeSelectOption(event) {
            this.isOption = null;
            this.filterSelect.parent_id = event.value;
            if (event.value) {
                const option = this.listOptions.find(
                    (value) => value.id == event.value
                ).code;
                this.isOption = option;
            }
            this.fetchEnumOptios();
        },
    },
};
</script>

<style></style>
