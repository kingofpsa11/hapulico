<template>
    <div>
        <b-row>
            <b-col sm="2" md="2" class="my-1">
                <b-button @click="createSellingPrice" variant="outline-primary"
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
                    <template #cell(name)="data">
                        {{ data.item.product.name }}
                    </template>
                    <template #cell(code)="data">
                        {{ data.item.product.code }}
                    </template>
                    <template v-slot:cell(action)="row">
                        <b-button-group>
                            <b-button
                                variant="primary"
                                @click="viewSellingPrice(row)"
                                >Xem</b-button
                            >
                            <b-button
                                variant="warning"
                                @click="editSellingPrice(row)"
                                >Sửa</b-button
                            >
                        </b-button-group>
                    </template>
                </b-table>
            </b-col>
        </b-row>
        <b-modal
            id="modal"
            title="Giá vật tư"
            @ok="storeSellingPrice"
            size="lg"
        >
            <b-form @submit.stop.prevent>
                <b-form-group label="Tên" label-cols="3">
                    <v-select
                        :options="listProducts"
                        @search="getProducts"
                        :filterable="false"
                        :value="product.name"
                        @input="updateProduct"
                    >
                        <template slot="no-options">
                            Chọn sản phẩm
                        </template>
                    </v-select>
                </b-form-group>
                <b-form-group label="Mã sản phẩm" label-cols="3">
                    <b-form-input
                        v-model="product.code"
                        type="text"
                        disabled
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Đơn vị tính" label-cols="3">
                    <b-form-input
                        v-model="product.unit"
                        type="text"
                        disabled
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Giá mua" label-cols="3">
                    <money
                        v-model="selling_price.price"
                        :disabled="!isEdit"
                        v-bind="money"
                        class="form-control"
                    ></money>
                </b-form-group>
                <b-form-group label="Ghi chú" label-cols="3">
                    <b-form-textarea
                        v-model="selling_price.note"
                        :disabled="!isEdit"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group label="Ngày" label-cols="3">
                    <b-form-datepicker
                        id="datepicker-dateformat2"
                        v-model="selling_price.effective_date"
                        :date-format-options="{
                            year: '2-digit',
                            month: '2-digit',
                            day: '2-digit'
                        }"
                        locale="vi"
                    ></b-form-datepicker>
                </b-form-group>
            </b-form>
            <template #modal-footer="{cancel}">
                <b-button variant="warning" @click="cancel()">Huỷ</b-button>
                <b-button
                    v-if="isEdit"
                    variant="primary"
                    @click="storeSellingPrice"
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
import vSelect from "vue-select";
import _ from "lodash";

export default {
    components: { vSelect },
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            pageOptions: [10, 20],
            sortBy: "name",
            sortDesc: false,
            fields: [
                { key: "index", label: "STT", sortable: true },
                { key: "code", label: "mã sản phẩm", sortable: true },
                { key: "name", label: "tên sản phẩm", sortable: true },
                {
                    key: "price",
                    label: "giá mua",
                    formatter: value => value.toLocaleString("vi")
                },
                { key: "effective_date", label: "Ngày" },
                { key: "note", label: "Ghi chú" },
                { key: "action", label: "xem" }
            ],
            items: [],
            errors: [],
            isBusy: false,
            selling_price: {},
            product: {},
            isEdit: false,
            listProducts: [],
            money: {
                decimal: ",",
                thousands: ".",
                precision: 0,
                masked: false
            }
        };
    },
    async created() {
        // this.getOptions();
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
        createSellingPrice() {
            this.selling_price = {
                product_id: "",
                price: 0,
                effective_date: null,
                status: 10,
                note: ""
            };
            this.product = {
                name: "",
                code: "",
                unit: ""
            };
            this.isEdit = true;
            this.$bvModal.show("modal");
        },
        async storeSellingPrice(e) {
            e.preventDefault();
            try {
                const response = await axios.post(
                    "/api/selling_prices",
                    this.selling_price
                );
                this.items.push(response.data);
                this.$nextTick(() => {
                    this.$bvModal.hide("modal");
                });
            } catch (error) {
                this.errors = error.response.data;
                console.log(error.response);
                this.$bvModal.hide("modal");
                // this.errors = error.response.data.errors.name;
            }
        },
        viewSellingPrice(row) {
            this.selling_price = row.item;
            this.product = row.item.product;
            this.isEdit = false;
            this.$bvModal.show("modal");
        },
        editSellingPrice(row) {
            this.selling_price = row.item;
            this.product = row.item.product;
            this.isEdit = true;
            this.$bvModal.show("modal");
        },
        dblclicked(selling_price) {
            this.selling_price = selling_price;
            this.isEdit = false;
            this.$bvModal.show("modal");
        },
        async getItem() {
            try {
                const response = await axios.get(
                    `api/selling_prices?perPage=${this.perPage}&page=${this.currentPage}`
                );
                this.items = response.data.data;
                this.totalRows = response.data.total;
            } catch (error) {
                console.log(error);
            }
        },
        getProducts(search, loading) {
            if (search.length) {
                loading(true);
                this.search(loading, search, this);
            }
        },
        search: _.debounce(async (loading, search, vm) => {
            try {
                const response = await axios.get(
                    `api/products/search?q=${encodeURI(search)}`
                );
                vm.listProducts = _.map(response.data, value => {
                    return {
                        label: value.name,
                        id: value.id,
                        code: value.code,
                        unit: value.unit
                    };
                });
                loading(false);
            } catch (error) {
                console.log(error);
            }
        }, 350),
        updateProduct(val) {
            this.selling_price.product_id = val.id;
            this.product.name = val.label;
            this.product.code = val.code;
            this.product.unit = val.unit;
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
