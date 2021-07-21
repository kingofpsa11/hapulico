<template>
    <div>
        <b-table :items="items" :fields="fields" hover>
            <template #cell(index)="data">
                {{ data.index }}
            </template>
            <template #cell(name)="data">
                <i>{{ data.value }}</i>
            </template>
        </b-table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            index: "index",
            items: [],
            fields: ["index", "name", "parent_id"]
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
