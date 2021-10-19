<template>
    <div>
        <b-row>
            <b-col class="my-1">
                <b-button @click="createContract" variant="outline-primary"
                    >Tạo Mới</b-button
                >
                <b-button @click="createExcel" variant="outline-primary"
                    >Excel</b-button
                >
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <v-server-table
                    :columns="columns"
                    url="/api/contracts"
                    :options="options"
                >
                    <template #action="props">
                        <div style="width: 0%">
                            <router-link
                                :to="`contract/${props.row.id}`"
                                class="btn btn-info"
                                >Xem</router-link
                            >
                            <router-link
                                :to="`contract/${props.row.id}/edit`"
                                class="btn btn-warning"
                                >Sửa</router-link
                            >
                        </div>
                    </template>
                </v-server-table>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import vSelect from "vue-select";
import ExcelJS from "exceljs";
import * as ExcelProper from "exceljs";
import FileSaver from "file-saver";

export default {
    components: { vSelect },
    data() {
        return {
            columns: [
                "customer_name",
                "date",
                "number",
                "product_name",
                "quantity",
                "selling_price",
                "deadline",
                "manufacturer_order_number",
                "status",
                "action"
            ],
            options: {
                headings: {
                    customer_name: "ĐVDH",
                    date: "Ngày lập",
                    number: "Số đơn hàng",
                    product_name: "Tên sản phẩm",
                    quantity: "Số lượng",
                    selling_price: "Đơn giá",
                    deadline: "Tiến độ",
                    manufacturer_order_number: "LSX",
                    status: "Trạng thái"
                },
                filterable: [
                    "customer_name",
                    "date",
                    "number",
                    "product_name",
                    "quantity",
                    "selling_price",
                    "deadline",
                    "manufacturer_order_number"
                ],
                filterByColumn: true,
                dateColumns: ["date", "deadline"],
                dateFormat: "DD/MM/YYYY",
                toMomentFormatter: "YYYY-MM-DD",
                alwaysShowPerPageSelect: true,
                perPage: 10,
                perPageValues: [5, 10, 25, 50, 100],
                datepickerOptions: {
                    showDropdowns: true
                },
                templates: {
                    date: function(h, row) {
                        return moment(row.date).format("DD/MM/YYYY");
                    },
                    deadline: function(h, row) {
                        return moment(row.deadline).format("DD/MM/YYYY");
                    },
                    selling_price: function(h, row) {
                        return parseInt(row.selling_price).toLocaleString("vi");
                    }
                }
            },
            money: {
                decimal: ",",
                thousands: ".",
                precision: 0,
                masked: false
            }
        };
    },
    async created() {
        // try {
        //     const response = await axios.get(`api/contracts`);
        //     this.items = response.data.data;
        // } catch (error) {
        //     console.log(error);
        // }
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
        },
        async createExcel() {
            const workbook = new ExcelJS.Workbook();
            var blobType =
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8";

            workbook.addWorksheet("mysheet", {
                views: [{ activeCell: "A1", showGridLines: true }]
            });

            const sheet = workbook.getWorksheet(1);
            sheet.mergeCells("A1:D1");
            sheet.mergeCells("E1:G1");

            var font = {
                name: "Times New Roman",
                size: 13,
                bold: true
            };

            sheet.properties.font = font;
            const cell = sheet.getCell("A1");
            cell.value = "ĐƠN HÀNG";
            cell.font = font;
            cell.alignment = { vertical: "middle", horizontal: "right" };
            sheet.getCell("E1").numFmt = '"So: "000"/DH2021/HPL"';
            sheet.getCell("E1").value = 108;
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: blobType });
            FileSaver(blob, "text.xlsx");
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

<style lang="scss">
.VueTables__date-filter {
    border: 1px solid #ccc;
    padding: 6px;
    border-radius: 4px;
    cursor: pointer;
    background-color: #fff;
    color: #798892;
}
</style>
