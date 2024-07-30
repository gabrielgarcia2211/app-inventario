<template>
    <Menubar
        :model="items"
        class="menubar-product"
        style="padding: 20px 10px; border-radius: 0px"
    >
        <template #start>
            <Button
                icon="pi pi-bars"
                @click="visible = true"
                severity="info"
                text
                raised
                style="margin-left: 10px"
            />
            <div class="custom-button-info">
                <Button
                    icon="pi pi-bell"
                    severity="info"
                    @click="notificationVisible = true"
                    :text="!cantNotifications"
                    raised
                />
                <span class="badge" v-if="cantNotifications">{{
                    cantNotifications
                }}</span>
            </div>
            <NotificationComponent
                v-if="notificationVisible"
                :notificationVisible="notificationVisible"
                @hidden="hiddenOutputNotification"
                @reload="reloadNotifications"
            >
            </NotificationComponent>
            <div></div>
        </template>
        <template #end>
            <Button
                icon="pi pi-sign-out"
                severity="danger"
                text
                raised
                rounded
                @click="logout"
            />
        </template>
    </Menubar>
    <Drawer v-model:visible="visible" class="custom-drawer">
        <template #header>
            <div class="flex items-center gap-2">
                <Avatar image="/img/dash.png" shape="circle" />
                <p
                    style="
                        font-family: 'Courier New', Courier, monospace;
                        font-size: 20px;
                        font-weight: bold;
                    "
                >
                    Menú
                </p>
            </div>
        </template>
        <hr />
        <ul class="menu-options">
            <li>
                <a href="/" class="p-menuitem-link menu-button">
                    <i class="pi pi-box p-menuitem-icon"></i>
                    <span class="p-menuitem-text">Productos</span>
                </a>
            </li>
            <li>
                <a href="/outflows" class="p-menuitem-link menu-button">
                    <i class="pi pi-address-book p-menuitem-icon"></i>
                    <span class="p-menuitem-text">Entrada de Productos</span>
                </a>
            </li>
            <li>
                <a href="/outflows" class="p-menuitem-link menu-button">
                    <i class="pi pi-sign-in p-menuitem-icon"></i>
                    <span class="p-menuitem-text">Salida de Productos</span>
                </a>
            </li>
        </ul>
    </Drawer>
</template>

<script>
import Avatar from "primevue/avatar";
import Drawer from "primevue/drawer";
import Menubar from "primevue/menubar";
import NotificationComponent from "./notification/NotificationComponent.vue";

export default {
    data() {
        return {
            visible: false,
            items: [],
            notificationVisible: false,
            cantNotifications: 0,
        };
    },
    components: {
        Avatar,
        Drawer,
        Menubar,
        NotificationComponent,
    },
    mounted() {
        this.loadNotifications();
    },
    methods: {
        loadNotifications() {
            this.$axios
                .get("/notifications/list/noread")
                .then((response) => {
                    const { data } = response.data;
                    this.cantNotifications = data;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        logout() {
            axios
                .post("/logout")
                .then((response) => {
                    window.location.href = "/login";
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        hiddenOutputNotification(status) {
            this.notificationVisible = status;
        },
        reloadNotifications() {
            this.loadNotifications();
        },
    },
};
</script>

<style>
.icon-bars {
    background-color: #0882b7 !important;
    border: 1px white solid !important;
}

.custom-drawer {
    width: 300px !important;
}

.custom-drawer .menu-options {
    list-style-type: none;
    margin: 0;
    padding: 0 10px;
}

.custom-drawer .menu-options li {
    display: flex;
    align-items: center;
    padding: 10px 5px;
    font-size: 16px;
    border: 1px solid transparent;
    border-radius: 8px;
    transition: background-color 0.3s, border-color 0.3s;
}

.custom-drawer .menu-options li i {
    margin-right: 10px;
}

.custom-drawer .menu-options li:hover {
    background-color: #f0f0f0;
    border-color: #ccc;
    cursor: pointer;
}

.custom-drawer .menu-options a {
    color: black;
    text-decoration: none !important;
    margin-top: 5px;
}

.custom-button-info {
    position: relative;
    display: inline-flex;
    align-items: center;
    margin-left: 20px;
}

.custom-button-info button {
    width: 40px;
}

.badge {
    position: absolute;
    right: -22px;
    top: -10px;
    background-color: red;
    color: white;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}
</style>
