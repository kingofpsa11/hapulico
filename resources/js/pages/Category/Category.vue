<template>
    <div>
        <b-table :items="items" :fields="fields" hover bordered>
            <template #cell(index)="data">
                {{ data.index }}
            </template>
            <template #cell(name)="data">
                <i>{{ data.value }}</i>
            </template>
            <template #cell(action)>
                <b-button variant="warning">Sửa</b-button>
            </template>
        </b-table>
        <b-button v-b-modal.modal-1 variant="outline-primary">Tạo Mới</b-button>
        <b-modal id="modal-1" title="Tạo mới sản phẩm" @ok="addCategory">
            <b-form @submit.stop.prevent>
                <b-form-group label="Tên nhóm" label-cols="4" label-for="name">
                    <b-form-input
                        v-model="form.name"
                        id="name"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Nhóm" label-cols="4" label-for="parent_id">
                    <b-form-select
                        v-model="form.parent_id"
                        :options="options"
                        value-field="id"
                        text-field="name"
                        class="mb-3"
                    >
                        <!-- This slot appears above the options from 'options' prop -->
                        <template #first>
                            <b-form-select-option :value="null" disabled
                                >-- Please select an option
                                --</b-form-select-option
                            >
                        </template>
                    </b-form-select>
                </b-form-group>
            </b-form>
            <template #modal-ok="{ok}">Thêm</template>
        </b-modal>
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
            options: [],
            items: [],
            fields: ["id", "name", "parent_id", "action"]
        };
    },
    async created() {
        try {
            const response = await axios.get(`api/categories`);
            this.items = response.data;
            this.options = response.data;
        } catch (error) {
            console.log(error);
        }
    },
    methods: {
        async addCategory(ev) {
            ev.preventDefault();
            if (this.form.name != "") {
                this.form.parent_id =
                    this.form.parent_id == "" ? 0 : this.form.parent_id;
                console.log(this.form.parent_id);
                try {
                    const response = await axios.post(
                        "api/categories",
                        this.form
                    );

                    this.items.push(response.data);
                    this.form.name = "";
                    this.form.parent_id = "";
                    this.$nextTick(() => {
                        this.$bvModal.hide("modal-1");
                    });
                } catch (error) {
                    console.log(error);
                }
            } else {
                this.id = "";
            }
        }
    }
};
</script>
