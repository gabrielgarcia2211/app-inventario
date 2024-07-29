<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management-product"
        :style="{ width: '30rem' }"
        :draggable="false"
        :position="'top'"
        :dismissableMask="true"
        @hide="onHide"
    >
        <template #header>
            <h3>Notificaciones</h3>
        </template>
        <DataTable
            v-model:filters="filters"
            :loading="loading"
            :value="products"
            :paginator="true"
            :rows="perPage"
            :totalRecords="totalRecords"
            :lazy="true"
            @page="onPage"
            @row-click="onRowClick"
            class="datatable-notification"
        >
            <Column field="id" style="padding: 0px">
                <template #body="slotProps">
                    <div
                        v-if="slotProps.data.read_at == null"
                        class="new-notification"
                    >
                        {{ JSON.parse(slotProps.data.data)["product_name"] }} |
                        {{ slotProps.data.notifiable_type.toUpperCase() }}
                    </div>
                    <div v-else class="old-notification">
                        {{ JSON.parse(slotProps.data.data)["product_name"] }} |
                        {{ slotProps.data.notifiable_type.toUpperCase() }}
                    </div>
                </template>
            </Column>
            <template #empty>
                <tr>
                    <td colspan="100%" class="empty-message">
                        No hay notificaciones disponibles.
                    </td>
                </tr>
            </template>
        </DataTable>
    </Dialog>
</template>

<script>
export default {
    props: ["notificationVisible"],
    data() {
        return {
            products: [],
            perPage: 5,
            totalRecords: 0,
            page: 1,
            filters: null,
            filtroInfo: [],
            loading: true,
            productId: null,
            visible: this.notificationVisible,
        };
    },
    components: {},
    watch: {},
    created() {},
    mounted() {
        this.fetchNotifications();
    },
    methods: {
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchNotifications();
        },
        updateStatus(id) {
            this.$axios
                .post("/notifications/update/status/" + id)
                .then((response) => {
                    this.$alertInfo("Notificación marcada", "", 500);
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        fetchNotifications() {
            this.loading = true;
            this.$axios
                .get("/notifications/list", {
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
        onRowClick(event) {
            this.updateStatus(event.data.id);
            this.$emit("reload", true);
            this.fetchNotifications();
        },
        rowClass(data, index) {
            return {
                "highlight-row": index === this.selectedRowIndex,
            };
        },
        onHide() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
    },
};
</script>
<style>
.new-notification {
    background-color: #2ca560;
    color: white;
    padding: 14px !important;
    cursor: pointer;
    border-radius: 5px;
}

.old-notification {
    padding: 14px !important;
    cursor: pointer;
}

.new-notification:hover {
    background-color: #51a575;
    color: white;
}
</style>
