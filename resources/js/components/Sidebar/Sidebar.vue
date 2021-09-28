<template>
    <div class="sidebar-wrapper">
        <nav
            :class="{ sidebar: true, sidebarStatic, sidebarOpened }"
            @mouseenter="sidebarMouseEnter"
            @mouseleave="sidebarMouseLeave"
        >
            <header class="logo">
                <router-link to="/app/dashboard"
                    ><span class="primary-word">Sing</span>
                    <span class="secondary-word"> App</span></router-link
                >
            </header>
            <h5 class="navTitle first">
                APP
            </h5>
            <ul class="nav">
                <NavLink
                    :activeItem="activeItem"
                    header="Dashboard"
                    link="/app/dashboard"
                    iconName="flaticon-home"
                    index="dashboard"
                    isHeader
                />
                <NavLink
                    :activeItem="activeItem"
                    header="Category"
                    link="/app/category"
                    iconName="flaticon-list"
                    index="category"
                    isHeader
                />
                <NavLink
                    :activeItem="activeItem"
                    header="Sản phẩm"
                    link="/app/product"
                    iconName="flaticon-equal-1"
                    index="tables"
                    isHeader
                />
                <NavLink
                    :activeItem="activeItem"
                    header="Giá vật tư"
                    link="/app/purchase_price"
                    iconName="flaticon-bell"
                    index="purchase_prices"
                    isHeader
                />
                <NavLink
                    :activeItem="activeItem"
                    header="Giá sản phẩm"
                    link="/app/selling_price"
                    iconName="flaticon-bell"
                    index="selling_prices"
                    isHeader
                />
                <NavLink
                    :activeItem="activeItem"
                    header="Đơn hàng"
                    link="/app/contract"
                    iconName="flaticon-network"
                    index="contract"
                    :childrenLinks="[
                        { header: 'Danh sách', link: '/app/contract' },
                        { header: 'Tạo mới', link: '/app/contract/create' }
                    ]"
                />
            </ul>
            <h5 class="navTitle">
                LABELS
            </h5>
            <ul class="sidebarLabels">
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-danger" />
                        <span class="labelName">Core</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-primary" />
                        <span class="labelName">UI Elements</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-success" />
                        <span class="labelName">Forms</span>
                    </a>
                </li>
            </ul>
            <h5 class="navTitle">
                PROJECTS
            </h5>
            <div class="sidebarAlerts">
                <b-alert
                    v-for="alert in alerts"
                    :key="alert.id"
                    class="sidebarAlert"
                    variant="transparent"
                    show
                    dismissible
                >
                    <span>{{ alert.title }}</span
                    ><br />
                    <b-progress
                        class="sidebarProgress progress-xs mt-1"
                        :variant="alert.color"
                        :value="alert.value"
                        :max="100"
                    />
                    <small>{{ alert.footer }}</small>
                </b-alert>
            </div>
        </nav>
    </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import isScreen from "@/core/screenHelper";
import NavLink from "./NavLink/NavLink";

export default {
    name: "Sidebar",
    components: { NavLink },
    data() {
        return {
            alerts: [
                {
                    id: 0,
                    title: "Sales Report",
                    value: 15,
                    footer: "Calculating x-axis bias... 65%",
                    color: "danger"
                },
                {
                    id: 1,
                    title: "Personal Responsibility",
                    value: 20,
                    footer: "Provide required notes",
                    color: "primary"
                }
            ]
        };
    },
    methods: {
        ...mapActions("layout", ["changeSidebarActive", "switchSidebar"]),
        setActiveByRoute() {
            const paths = this.$route.fullPath.split("/");
            paths.pop();
            this.changeSidebarActive(paths.join("/"));
        },
        sidebarMouseEnter() {
            if (!this.sidebarStatic && (isScreen("lg") || isScreen("xl"))) {
                this.switchSidebar(false);
                this.setActiveByRoute();
            }
        },
        sidebarMouseLeave() {
            if (!this.sidebarStatic && (isScreen("lg") || isScreen("xl"))) {
                this.switchSidebar(true);
                this.changeSidebarActive(null);
            }
        }
    },
    created() {
        this.setActiveByRoute();
    },
    computed: {
        ...mapState("layout", {
            sidebarStatic: state => state.sidebarStatic,
            sidebarOpened: state => !state.sidebarClose,
            activeItem: state => state.sidebarActiveElement
        })
    }
};
</script>

<!-- Sidebar styles should be scoped -->
<style src="./Sidebar.scss" lang="scss" scoped />
