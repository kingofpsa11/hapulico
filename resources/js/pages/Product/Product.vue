<template>
    <div>
        <b-row>
            <b-col sm="2" md="2" class="my-1">
                <b-button @click="createProduct" variant="outline-primary"
                    >Tạo Mới</b-button
                >
            </b-col>
            <b-col sm="3" md="4" class="my-1">
                <b-form-group
                    label="Per page"
                    label-cols-sm="5"
                    label-cols-md="4"
                    label-cols-lg="3"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="perPageSelect"
                    class="mb-0"
                >
                    <b-form-select
                        v-model="perPage"
                        id="perPageSelect"
                        size="md"
                        :options="pageOptions"
                    ></b-form-select>
                </b-form-group>
            </b-col>

            <b-col sm="7" md="6" class="my-1 overflow-auto">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    aria-controls="table"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <b-table
                    id="table"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="0"
                    head-variant="primary"
                    bordered
                    striped
                    hover
                    selectable
                    select-mode="single"
                    :items="items"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    @row-dblclicked="dblclicked"
                >
                    <template v-slot:cell(action)="row">
                        <b-button-group>
                            <b-button
                                variant="primary"
                                @click="viewProduct(row)"
                                >Xem</b-button
                            >
                            <b-button
                                variant="warning"
                                @click="editProduct(row)"
                                >Sửa</b-button
                            >
                        </b-button-group>
                    </template>
                </b-table>
            </b-col>
        </b-row>

        <b-modal
            id="modal"
            title="Tạo mới sản phẩm"
            @ok="storeProduct"
            size="xl"
        >
            <b-form @submit.stop.prevent>
                <b-row>
                    <b-col md="6">
                        <b-form-group label="Nhóm sản phẩm">
                            <b-form-select
                                v-model="product.category_id"
                                required
                                :options="categories"
                                :disabled="!isEdit"
                            >
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Mã sản phẩm">
                            <b-form-input
                                v-model="product.code"
                                type="text"
                                required
                                :disabled="!isEdit"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Tên sản phẩm">
                            <b-form-input
                                v-model="product.name"
                                type="text"
                                required
                                :disabled="!isEdit"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Tên sản phẩm hoá đơn">
                            <b-form-input
                                v-model="product.name_bill"
                                type="text"
                                :disabled="!isEdit"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Đơn vị tính">
                            <b-form-input
                                v-model="product.unit"
                                type="text"
                                :disabled="!isEdit"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col md="6">
                        <b-form-group label="Ghi chú">
                            <b-form-textarea
                                v-model="product.note"
                                :disabled="!isEdit"
                            ></b-form-textarea>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group label="Bản vẽ">
                            <b-form-file></b-form-file>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>
            <template #modal-footer="{cancel}">
                <b-button variant="warning" @click="cancel()">Huỷ</b-button>
                <b-button v-if="isEdit" variant="primary" @click="storeProduct"
                    >Lưu</b-button
                >
                <b-button v-else variant="success" @click="isEdit = !isEdit"
                    >Sửa</b-button
                >
            </template>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 100,
            perPage: 10,
            pageOptions: [10, 20],
            sortBy: "name",
            sortDesc: false,
            fields: [
                { key: "category_id", label: "nhóm", sortable: true },
                { key: "code", label: "mã sản phẩm", sortable: true },
                { key: "name", label: "tên sản phẩm", sortable: true },
                { key: "status", label: "trạng thái" },
                { key: "action", label: "xem" }
            ],
            items: [],
            errors: [],
            isBusy: false,
            product: {},
            isEdit: false,
            categories: []
        };
    },
    async created() {
        this.getOptions();
        this.getItem();
    },
    methods: {
        async getOptions() {
            try {
                const response = await axios.get("/api/categories");
                this.categories.push({
                    value: null,
                    text: "Chọn nhóm sản phẩm"
                });
                response.data.forEach(category => {
                    this.categories.push({
                        value: category.id,
                        text: category.name
                    });
                });
            } catch (error) {
                this.errors = error.response.data.errors.name;
            }
        },
        createProduct() {
            this.product = {
                category_id: null,
                code: "",
                unit: "",
                status: 10,
                note: ""
            };
            this.isEdit = true;
            this.$bvModal.show("modal");
        },
        async storeProduct(e) {
            e.preventDefault();
            try {
                const response = await axios.post(
                    "/api/products",
                    this.product
                );

                this.items.push(response.data);
                this.$nextTick(() => {
                    this.$bvModal.hide("modal");
                });
            } catch (error) {
                this.errors = error.response.data.errors.name;
            }
        },
        viewProduct(product) {
            this.product = product.item;
            this.isEdit = false;
            this.$bvModal.show("modal");
        },
        editProduct(product) {
            this.product = product.item;
            this.isEdit = true;
            this.$bvModal.show("modal");
        },
        dblclicked(product) {
            this.product = product;
            this.isEdit = false;
            this.$bvModal.show("modal");
        },
        async getItem() {
            try {
                const response = await axios.get(
                    `api/products?perPage=${this.perPage}&page=${this.currentPage}`
                );
                this.items = response.data.data;
                this.totalRows = response.data.total;
            } catch (error) {
                console.log(error);
            }
        }
    },
    watch: {
        currentPage: function() {
            this.getItem();
        },
        perPage: function() {
            this.getItem();
        }
    }
};
</script>
