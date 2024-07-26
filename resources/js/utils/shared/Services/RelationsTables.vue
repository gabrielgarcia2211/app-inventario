<script>
export default {
    methods: {
        $relationTableProducts(selector) {
            switch (selector) {
                default:
                    return selector;
            }
        },
        $relationTableProductOutflow(selector) {
            switch (selector) {
                case "fecha_salida":
                    return "products.created_at";
                default:
                    return selector;
            }
        },
    },
};
</script>
