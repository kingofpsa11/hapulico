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
                        <b-form-input
                            v-model="form.name"
                            id="name"
                            required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                        label="Nhóm"
                        label-cols="4"
                        label-for="parent_id"
                    >
                        <b-form-input
                            v-model="form.parent_id"
                            id="parent_id"
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <b-button
                            variant="outline-primary"
                            @click="addCategory"
                            v-if="id == ''"
                            >Save</b-button
                        >
                        <b-button
                            variant="outline-warning"
                            @click="updateCategory"
                            v-else
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
        if (this.$route.params.id) {
            this.id = this.$route.params.id;
            try {
                const response = await axios.get(`api/categories/${this.id}`);
                this.form = response.data;
            } catch (error) {
                console.log(error);
            }
        } else {
            this.id = "";
        }
    },
    methods: {
        async addCategory() {
            if (this.form.name != "") {
                try {
                    const response = await axios.post(
                        "api/categories",
                        this.form
                    );
                    this.$router.push({
                        name: "ViewCategory",
                        params: { id: response.data.id }
                    });
                } catch (error) {
                    console.log(error);
                }
            } else {
                this.id = "";
            }
        },
        async updateCategory() {
            if (this.form.name != "") {
                try {
                    const response = await axios.put(
                        `api/categories/${this.id}`,
                        this.form
                    );
                    this.$router.push({
                        name: "ViewCategory",
                        params: { id: response.data.id }
                    });
                } catch (error) {
                    console.log(error);
                }
            }
        }
    }
};
</script>
