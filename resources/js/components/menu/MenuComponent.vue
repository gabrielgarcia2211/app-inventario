<template>
    <div>
        <Menubar
            :model="items"
            class="menubar-product"
            style="padding: 20px 10px; border-radius: 0px"
        >
            <template #start>
                <div class="menu-container">
                    <a href="/" class="p-menuitem-link menu-button">
                        <i class="pi pi-home p-menuitem-icon"></i>
                        <span class="p-menuitem-text">Productos</span>
                    </a>
                    <a href="/outflows" class="p-menuitem-link menu-button">
                        <i class="pi pi-sign-out p-menuitem-icon"></i>
                        <span class="p-menuitem-text">Salida de Productos</span>
                    </a>
                </div>
            </template>
            <template #end>
                <button
                    @click="logout"
                    class="p-button p-component p-button-text"
                >
                    <span class="p-menuitem-text">Salir</span>
                </button>
            </template>
        </Menubar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
        };
    },
    methods: {
        logout() {
            axios
                .post("/logout")
                .then((response) => {
                    window.location.href = "/login";
                })
                .catch((error) => {
                    console.error("Error during logout:", error);
                });
        },
    },
};
</script>

<style>
.menubar-product {
    background-color: #e15b5b !important;
}

button.p-paginator-page.p-paginator-page-selected {
    background-color: #e15b5b !important;
    color: white;
}

.p-menuitem-link {
    display: flex;
    align-items: center;
    color: white;
    font-size: 16px;
    text-decoration: none;
    cursor: pointer;
}

.p-menuitem-link:hover {
    color: black;
}

.p-menuitem-icon {
    margin-right: 8px;
}

button.p-button.p-component.p-button-text {
    color: white;
    font-size: 16px;
    border: none;
    background: none;
    cursor: pointer;
}

button.p-button.p-component.p-button-text:hover {
    color: black;
}

.menu-container {
    display: flex;
    gap: 10px;
}

.menu-button {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background-color: #e35050;
    color: white;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.menu-button:hover {
    background-color: #e46d6d;
}

.menu-button .p-menuitem-icon {
    margin-right: 8px;
}
</style>
