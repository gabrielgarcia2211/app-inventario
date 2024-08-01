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
                    return "enum_options.name";
                case "date_out":
                    return "product_outflows.created_at";
                default:
                    return selector;
            }
        },
        $relationTableProductInput(selector) {
            switch (selector) {
                case "seamstress":
                    return "enum_options.name";
                case "date_entry":
                    return "product_inputs.created_at";
                default:
                    return selector;
            }
        },
        $relationTableEnumOption(selector) {
            switch (selector) {
                default:
                    return selector;
            }
        },
    },
};
</script>
