<template>
    <div>
        <b-table :items="items" :fields="fields" hover> </b-table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            fields: ["name", "parent_id"]
        };
    },
    async created() {
        try {
            const response = await axios.get(`api/categories`);
            this.items = response.data;
        } catch (error) {
            console.log(error);
        }
    },
    methods: {}
};
</script>
