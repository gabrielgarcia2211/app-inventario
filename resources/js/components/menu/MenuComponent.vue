<template>
    <Menubar
        :model="items"
        class="menubar-product"
        style="padding: 20px 10px; border-radius: 0px"
    ></Menubar>
</template>

<script>
export default {
    data() {
        return {
            items: [
                {
                    label: "Productos",
                    icon: "pi pi-home",
                    to: "/",
                    class: "icon-product-home",
                },
            ],
        };
    },
    components: {},
    created() {},
    mounted() {},
    methods: {},
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

.icon-product-home .p-menubar-item-label {
    color: rgb(255, 255, 255);
    font-size: 16px;
}

.icon-product-home:hover .p-menubar-item-label {
    color: black;
}

.icon-product-home .p-menubar-item-link .p-menubar-item-icon {
    color: rgb(255, 255, 255);
    font-size: 16px;
}
</style>
