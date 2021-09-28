<template>
    <div>
        <b-row>
            <b-col sm="2" md="2" class="my-1">
                <b-button @click="createContract" variant="outline-primary"
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
                    :per-page="0"
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
                    @row-dblclicked=""
                >
                    <template slot="top-row" slot-scope="{ fields }">
                        <td v-for="field in fields" :key="field.key">
                            <b-input :placeholder="field.label" />
                        </td>
                    </template>
                    <template #cell(index)="row">
                        {{ row.index + 1 }}
                    </template>
                    <template #cell(action)="row">
                        <div>
                            <b-button
                                variant="primary"
                                @click="viewContract(row)"
                                >Xem</b-button
                            >
                        </div>
                        <div>
                            <b-button
                                variant="warning"
                                @click="editContract(row)"
                                >Sửa</b-button
                            >
                        </div>
                    </template>
                </b-table>
            </b-col>
        </b-row>
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
                {
                    key: "date",
                    label: "Ngày",
                    sortable: true,
                    formatter: value =>
                        new Date(value).toLocaleDateString("vi", {
                            year: "numeric",
                            month: "2-digit",
                            day: "2-digit"
                        })
                },
                {
                    key: "customer.company_name",
                    label: "Công ty",
                    sortable: true
                },
                { key: "product.code", label: "mã sản phẩm", sortable: true },
                { key: "product.name", label: "tên sản phẩm", sortable: true },
                { key: "quantity", label: "Số lượng", sortable: true },
                {
                    key: "price",
                    label: "giá mua",
                    formatter: value => value.toLocaleString("vi")
                },
                {
                    key: "deadline",
                    label: "tiến độ",
                    formatter: value =>
                        new Date(value).toLocaleDateString("vi", {
                            year: "numeric",
                            month: "2-digit",
                            day: "2-digit"
                        })
                },
                { key: "manufacturer_order_number", label: "LSX" },
                { key: "note", label: "Ghi chú" },
                { key: "action", label: "xem" }
            ],
            items: [],
            errors: [],
            isBusy: false,
            contract: {},
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
        try {
            const response = await axios.get(`api/contracts`);
            this.items = response.data.data;
        } catch (error) {
            console.log(error);
        }
    },
    methods: {
        createContract() {
            this.$router.push("contract/create");
        },
        viewContract(row) {
            this.$router.push(`contract/${row.item.id}`);
        },
        editContract(row) {
            this.contract = row.item;
            this.product = row.item.product;
            this.isEdit = true;
            this.$bvModal.show("modal");
        },
        dblclicked(contract) {
            this.contract = contract;
            this.isEdit = false;
            this.$bvModal.show("modal");
        },
        async getItem() {
            try {
                const response = await axios.get(
                    `api/contracts?perPage=${this.perPage}&page=${this.currentPage}`
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
            this.contract.product_id = val.id;
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
