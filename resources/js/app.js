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
import axios from "axios";

// componentes generales
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Card from "primevue/card";

const app = createApp({});

app.use(PrimeVue, {
    theme: {
        preset: Aura,
    },
    zIndex: {
        modal: 1000, //dialog, sidebar
        overlay: 9999, //dropdown, overlaypanel
        menu: 1000, //overlay menus
        tooltip: 1100, //tooltip
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

import ExampleComponent from "./components/ExampleComponent.vue";
app.component("example-component", ExampleComponent);

// Configura Axios globalmente
app.config.globalProperties.$axios = axios;

// Localidad de PrimeVue
app.config.globalProperties.$primevue.config.locale = esLocale;

app.mount("#app");
