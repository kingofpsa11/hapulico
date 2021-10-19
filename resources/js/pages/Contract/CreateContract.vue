<template>
    <div>
        <b-card
            header="Tạo mới đơn hàng"
            header-border-variant="primary"
            header-bg-variant="primary"
            header-text-variant="white"
            border-variant="primary"
        >
            <b-form>
                <b-form-group
                    label="Khách hàng"
                    label-cols="2"
                    label-for="customer_name"
                >
                    <v-select
                        :options="listCustomers"
                        @search="getCustomers"
                        :filterable="false"
                        :value="customer.name"
                        @input="updateCustomer"
                        required
                    >
                        <template slot="no-options">
                            Chọn khách hàng
                        </template>
                    </v-select>
                </b-form-group>
                <b-form-row>
                    <b-col>
                        <b-form-group
                            label="Số đơn hàng"
                            label-cols="4"
                            label-for="number"
                        >
                            <b-form-input
                                name="number"
                                id="number"
                                v-model="contract.number"
                                type="number"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group
                            label="Ngày đặt hàng"
                            label-cols="4"
                            label-for="date"
                        >
                            <b-form-datepicker
                                id="date"
                                v-model="contract.date"
                                :date-format-options="{
                                    year: '2-digit',
                                    month: '2-digit',
                                    day: '2-digit'
                                }"
                                locale="vi"
                            ></b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group
                            label="Số LSX"
                            label-cols="4"
                            label-for="manufacturer_order_id"
                        >
                            <b-form-input
                                name="manufacturer_order_id"
                                id="manufacturer_order_id"
                                class="mb-2"
                                v-model="contract.total"
                                type="number"
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-form-row>

                <b-form-row>
                    <b-col>
                        <b-table
                            id="table"
                            :fields="fields"
                            head-variant="primary text-center align-middle"
                            bordered
                            striped
                            hover
                            small
                            :items="contract.details"
                        >
                            <template #cell(product.name)="row">
                                <v-select
                                    :options="listPrices"
                                    @search="getPrices"
                                    :filterable="false"
                                    :value="row.item.product.name"
                                    @input="val => updatePrice(row, val)"
                                    required
                                >
                                    <template v-slot:no-options>
                                        Chọn sản phẩm
                                    </template></v-select
                                >
                            </template>
                            <template #cell(quantity)="row">
                                <money
                                    v-model="row.item.quantity"
                                    v-bind="money"
                                    class="form-control"
                                    @input.native="update(row.item)"
                                ></money>
                            </template>
                            <template #cell(price)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(profit_rate_100)="row">
                                <b-input-group>
                                    <money
                                        v-model="row.item.profit_rate_100"
                                        v-bind="money"
                                        class="form-control"
                                        @input="update(row.item)"
                                    ></money>
                                    <b-input-group-append is-text>
                                        <b-icon icon="percent"></b-icon>
                                    </b-input-group-append>
                                </b-input-group>
                            </template>
                            <template #cell(discount)="row">
                                <money
                                    v-model="row.item.discount"
                                    v-bind="money"
                                    class="form-control"
                                    @change.native="update(row.item)"
                                ></money>
                            </template>
                            <template #cell(selling_price)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(deadline)="row">
                                <b-form-datepicker
                                    v-model="row.item.deadline"
                                    :date-format-options="{
                                        year: '2-digit',
                                        month: '2-digit',
                                        day: '2-digit'
                                    }"
                                    locale="vi"
                                ></b-form-datepicker>
                            </template>
                            <template #cell(note)="row">
                                <b-form-input
                                    v-model="row.item.note"
                                ></b-form-input>
                            </template>
                            <template v-slot:cell(action)="row">
                                <b-button-group>
                                    <b-button
                                        variant="warning"
                                        @click="removeRow(row.index)"
                                        v-if="numberOfRows != 1"
                                        >-</b-button
                                    >
                                    <b-button
                                        variant="success"
                                        @click="addRow"
                                        v-if="row.index + 1 == numberOfRows"
                                        >+</b-button
                                    >
                                </b-button-group>
                            </template>
                        </b-table>
                    </b-col>
                </b-form-row>
            </b-form>
            <template #footer>
                <div class="float-right">
                    <b-button variant="warning">Huỷ</b-button>
                    <b-button variant="primary" @click="storeContract"
                        >Lưu</b-button
                    >
                </div>
            </template>
        </b-card>
    </div>
</template>

<script>
import vSelect from "vue-select";
import _ from "lodash";

export default {
    components: { vSelect },
    data() {
        return {
            errors: [],
            isEdit: false,
            id: "",
            item: {
                id: "",
                product: {
                    code: "",
                    name: "",
                    unit: ""
                },
                price: 0,
                selling_price_id: 0,
                quantity: 0,
                profit_rate_100: 0,
                profit_rate: 0,
                discount: 0,
                selling_price: 0,
                deadline: "",
                note: ""
            },
            contract: {
                date: "",
                customer_id: "",
                number: 0,
                manufacturer_order_number: 0,
                total: 0,
                details: []
            },
            listPrices: [],
            customer: {},
            state: "",
            listCustomers: [],
            fields: [
                { key: "index", label: "STT" },
                { key: "product.code", label: "mã sản phẩm" },
                { key: "product.name", label: "tên sản phẩm" },
                { key: "product.unit", label: "Đơn vị" },
                { key: "quantity", label: "Số lượng" },
                { key: "price", label: "Giá Bắc" },
                { key: "profit_rate_100", label: "Lãi" },
                { key: "discount", label: "Giảm giá" },
                { key: "selling_price", label: "Giá bán" },
                { key: "deadline", label: "Tiến độ" },
                { key: "note", label: "Ghi chú" },
                { key: "action", label: "xem" }
            ],
            money: {
                decimal: ",",
                thousands: ".",
                precision: 0,
                masked: false
            }
        };
    },
    async created() {
        this.contract.details.push({ ...this.item });
        if (typeof this.$route.params.id !== "undefined") {
            this.isEdit = true;
            this.id = this.$route.params.id;
            const response = await axios.get(`/api/contracts/${this.id}`);
            this.contract = response.data.data;
        }
    },
    methods: {
        async storeContract(e) {
            e.preventDefault();
            try {
                const response = await axios.post(
                    "/api/contracts",
                    this.contract
                );
                this.$router.push(`${response.data.id}`);
            } catch (error) {
                this.errors = error.response.data;
                console.log(error.response);
            }
        },
        getCustomers(search, loading) {
            loading(true);
            this.searchCustomer(loading, search, this);
        },
        searchCustomer: _.debounce(async (loading, search, vm) => {
            try {
                const response = await axios.get(
                    `api/customers/search?q=${encodeURI(search)}`
                );
                vm.listCustomers = _.map(response.data, value => {
                    return {
                        label: value.company_name,
                        id: value.id
                    };
                });
                loading(false);
            } catch (error) {
                console.log(error);
            }
        }, 200),
        updateCustomer(val) {
            this.contract.customer_id = val.id;
            this.customer.company_name = val.label;
        },
        getPrices(search, loading) {
            if (search.length) {
                loading(true);
                this.searchPrice(loading, search, this);
            }
        },
        searchPrice: _.debounce(async (loading, search, vm) => {
            try {
                const response = await axios.get(
                    `api/selling_prices/search?q=${encodeURI(search)}`
                );
                vm.listPrices = _.map(response.data, value => {
                    return {
                        label: value.product.name,
                        id: value.id,
                        price: value.price,
                        product: value.product
                    };
                });

                // vm.listPrices = _.map(response.data, value => {
                loading(false);
            } catch (error) {
                console.log(error);
            }
        }, 200),
        updatePrice(row, val) {
            if (val != null) {
                row.value = val.label ?? "";
                row.item.product = val.product ?? "";
                row.item.price = val.price ?? "";
                row.item.selling_price_id = val.id ?? "";
                row.item.selling_price =
                    Math.round(
                        (row.item.price * (1 + row.item.profit_rate / 100) +
                            row.item.discount) /
                            1000
                    ) * 1000 ?? "";
            } else {
                row.value = "";
                row.item.product = "";
                row.item.price = "";
                row.item.selling_price_id = "";
                row.item.selling_price = "";
            }
        },
        addRow() {
            this.contract.details.push({ ...this.item });
        },
        removeRow(index) {
            this.contract.details.splice(index, 1);
        },
        update(row) {
            row.selling_price =
                Math.round(
                    (row.price * (1 + row.profit_rate) + row.discount) / 1000
                ) * 1000;
            row.profit_rate = row.profit_rate_100 / 100;
            this.contract.details.forEach(item => {
                this.contract.total = item.selling_price * item.quantity;
            });
        }
    },
    computed: {
        profitRate() {
            this.contract.details.forEach(item => {
                item.profit_rate = item.profit_rate_100 / 100;
            });
        },
        numberOfRows() {
            return this.contract.details.length;
        }
    }
};
</script>
