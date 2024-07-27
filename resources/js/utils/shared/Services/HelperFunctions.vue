<script>
export default {
    methods: {
        $getEnumProductSize() {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/products/list/size`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $getEnumProductCategory() {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/products/list/category`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $getEnumProductSection() {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/products/list/section`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $getEnumClientName() {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/clients/list/name`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $formatPrice(value) {
            if (isNaN(value)) return value;
            return new Intl.NumberFormat("es-CO", {
                style: "currency",
                currency: "COP",
            }).format(value);
        },
        $formatNumber(value) {
            if (isNaN(value)) return value;
            const number = Math.floor(parseFloat(value));
            return number.toLocaleString("es-ES").replace(",", ".");
        },
        $formatTextSize(value) {
            if (!value) return value;
            if (value == "ALL") {
                return "TOTAL";
            } else {
                return value;
            }
        },
        $parseSizes(sizeString) {
            if (!sizeString) return [];
            return sizeString.split(";").map((sizePair) => {
                const [size, quantity] = sizePair.split(":");
                return { size, quantity: parseInt(quantity) };
            });
        },
    },
};
</script>
