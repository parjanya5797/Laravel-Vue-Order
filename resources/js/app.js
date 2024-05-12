import "./bootstrap";
import { createApp } from "vue";
import IndexTable from "./components/IndexTable.vue";
import App from "./App.vue";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App);

app.component("IndexTable",IndexTable);
app.use(Toast,{position:'bottom-center'});
app.mount("#app");