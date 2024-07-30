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
                case "client":
                    return "product_outflows.client_name";
                case "fecha_salida":
                    return "product_outflows.created_at";
                default:
                    return selector;
            }
        },
    },
};
</script>
