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
                    <b-form-input
                        :value="contract.customer.company_name"
                        disabled
                    ></b-form-input>
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
                                disabled
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
                                disabled
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
                                v-model="contract.manufacturer_order_number"
                                type="number"
                                disabled
                            ></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-form-row>

                <b-form-row>
                    <b-col>
                        <b-table
                            id="table"
                            :fields="fields"
                            head-variant="primary text-center"
                            bordered
                            hover
                            :items="contract.contract_details"
                        >
                            <template #cell(index)="row">
                                {{ row.index + 1 }}
                            </template>
                            <template #cell(quantity)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(price)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(profit_rate)="row">
                                {{ row.value.toLocaleString("vi") }}%
                            </template>
                            <template #cell(discount)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(selling_price)="row">
                                {{ row.value.toLocaleString("vi") }}
                            </template>
                            <template #cell(deadline)="row" class="text-right">
                                {{
                                    new Date(row.value).toLocaleDateString(
                                        "vi",
                                        {
                                            year: "numeric",
                                            month: "2-digit",
                                            day: "2-digit"
                                        }
                                    )
                                }}
                            </template>
                            <template #cell(note)="row">
                                {{ row.value }}
                            </template>
                        </b-table>
                    </b-col>
                </b-form-row>
            </b-form>
            <template #footer>
                <div class="float-right">
                    <b-button variant="warning">Huỷ</b-button>
                    <b-button variant="primary" @click="updateContract()"
                        >Sửa</b-button
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
            contract: {
                customer: {},
                date: "",
                customer_id: "",
                number: 0,
                manufacturer_order_number: 0,
                total: 0,
                contract_details: []
            },
            listPrices: [],
            state: "",
            fields: [
                { key: "index", label: "STT" },
                {
                    key: "product.code",
                    label: "mã sản phẩm"
                },
                {
                    key: "product.name",
                    label: "tên sản phẩm"
                },
                {
                    key: "product.unit",
                    label: "Đơn vị",
                    tdClass: "text-center"
                },
                {
                    key: "quantity",
                    label: "Số lượng",
                    tdClass: "text-center"
                },
                {
                    key: "price",
                    label: "Giá Bắc",
                    tdClass: "text-right"
                },
                {
                    key: "profit_rate",
                    label: "Lãi",
                    tdClass: "text-right"
                },
                {
                    key: "discount",
                    label: "Giảm giá",
                    tdClass: "text-right"
                },
                {
                    key: "selling_price",
                    label: "Giá bán",
                    tdClass: "text-right"
                },
                {
                    key: "deadline",
                    label: "Tiến độ",
                    tdClass: "text-right"
                },
                {
                    key: "note",
                    label: "Ghi chú",
                    tdClass: "text-right"
                }
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
        if (this.$route.params.id) {
            this.id = this.$route.params.id;
            this.showContract(this.id);
        }
    },
    methods: {
        async showContract(id) {
            try {
                const response = await axios.get(`/api/contracts/${id}`);
                this.contract = response.data.data;
            } catch (error) {
                this.errors = error.response.data;
            }
        },
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
            this.contract.contract_details.push({ ...this.item });
        },
        removeRow(index) {
            this.contract.contract_details.splice(index, 1);
        },
        update(row) {
            row.selling_price =
                Math.round(
                    (row.price * (1 + row.profit_rate) + row.discount) / 1000
                ) * 1000;
            row.profit_rate = row.profit_rate_100 / 100;
            this.contract.contract_details.forEach(item => {
                this.contract.total = item.selling_price * item.quantity;
            });
        },
        updateContract() {
            this.$router.push(`${this.id}/edit`);
        }
    },
    computed: {
        numberOfRows() {
            // return this.contract.contract_details.length;
        }
    }
};
</script>
