<template>
    <div>
        <b-card border-variant="primary" no-body>
            <b-card-header
                header="Thêm mới"
                header-bg-variant="primary"
                header-text-variant="white"
            ></b-card-header>
            <b-card-body>
                <b-form>
                    <b-form-group
                        label="Tên nhóm"
                        label-cols="4"
                        label-for="name"
                    >
                        {{ form.name }}
                    </b-form-group>
                    <b-form-group
                        label="Nhóm"
                        label-cols="4"
                        label-for="parent_id"
                    >
                        {{ form.parent_id }}
                    </b-form-group>
                    <b-form-group>
                        <b-button variant="outline-primary" @click="edit"
                            >Edit</b-button
                        >
                    </b-form-group>
                </b-form>
            </b-card-body>
        </b-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: "",
                parent_id: ""
            },
            id: ""
        };
    },
    async created() {
        this.id = this.$route.params.id;
        try {
            const response = await axios.get(`api/categories/${this.id}`);
            this.form = response.data;
        } catch (error) {
            console.log(error);
        }
    },
    methods: {
        edit() {
            this.$router.push(`${this.id}/edit`);
        }
    }
};
</script>
