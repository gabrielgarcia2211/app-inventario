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
    },
};
</script>
