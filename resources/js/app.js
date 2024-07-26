/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import { createApp } from "vue";
import PrimeVue from "primevue/config";
import esLocale from "./translations/primevue-es.json";
import Aura from "@primevue/themes/aura";
import "primeicons/primeicons.css";

// Importacion de funciones compartidas
import shared from "./utils/shared";
import Swal from "sweetalert2";
import axios from "axios";
import $ from "jquery";

// componentes generales
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Card from "primevue/card";
import Menubar from "primevue/menubar";
import Dialog from "primevue/dialog";
import InputNumber from "primevue/inputnumber";
import FloatLabel from "primevue/floatlabel";
import Image from "primevue/image";
import Select from "primevue/select";
import Tag from "primevue/tag";

const app = createApp({});

app.use(PrimeVue, {
    theme: {
        preset: Aura,
    },
});

app.mixin(shared.AlertsComponent);
app.mixin(shared.ReadHttpStatusErrors);
app.mixin(shared.HelperFunctions);
app.mixin(shared.RelationsTables);

// Anexo de componentes de vuejs
app.component("DataTable", DataTable);
app.component("Column", Column);
app.component("Button", Button);
app.component("InputText", InputText);
app.component("Card", Card);
app.component("Menubar", Menubar);
app.component("Dialog", Dialog);
app.component("InputNumber", InputNumber);
app.component("FloatLabel", FloatLabel);
app.component("Image", Image);
app.component("Select", Select);
app.component("Tag", Tag);

import ProductComponent from "./components/inventory/ProductComponent.vue";
app.component("product-component", ProductComponent);

import ProductOutflowComponent from "./components/outflows/ProductOutflowComponent.vue";
app.component("product-outflow-component", ProductOutflowComponent);

import MenuComponent from "./components/menu/MenuComponent.vue";
app.component("menu-component", MenuComponent);

// Configura Axios globalmente
app.config.globalProperties.$axios = axios;

// Localidad de PrimeVue
app.config.globalProperties.$primevue.config.locale = esLocale;

// Configura SweetAlert2 globalmente
app.config.globalProperties.$swal = Swal;

window.$ = $;
window.jQuery = $;

app.mount("#app");
