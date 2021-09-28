import Vue from "vue";
import Router from "vue-router";

import Layout from "@/components/Layout/Layout";
import Login from "@/pages/Login/Login";
import Category from "@/pages/Category/Category";

import Product from "@/pages/Product/Product";
import PurchasePrice from "@/pages/PurchasePrice/PurchasePrice";
import SellingPrice from "@/pages/SellingPrice/SellingPrice";
import Contract from "@/pages/Contract/Contract";
import ListContract from "@/pages/Contract/ListContract";
import CreateContract from "@/pages/Contract/CreateContract";
import ViewContract from "@/pages/Contract/ViewContract";
// import ErrorPage from "@/pages/Error/Error";
// Core
// import TypographyPage from "@/pages/Typography/Typography";

// // Tables
// import TablesBasicPage from "@/pages/Tables/Basic";

// // Maps
// import GoogleMapPage from "@/pages/Maps/Google";

// Main
import AnalyticsPage from "@/pages/Dashboard/Dashboard";

// Charts
// import ChartsPage from "@/pages/Charts/Charts";

// Ui
// import IconsPage from "@/pages/Icons/Icons";
// import NotificationsPage from "@/pages/Notifications/Notifications";

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: "/login",
            name: "Login",
            component: Login
        },
        // {
        //     path: "/error",
        //     name: "Error",
        //     component: ErrorPage
        // },

        {
            path: "/app",
            name: "Layout",
            component: Layout,
            children: [
                {
                    path: "dashboard",
                    name: "AnalyticsPage",
                    component: AnalyticsPage
                },
                {
                    path: "category",
                    name: "Category",
                    component: Category
                },
                {
                    path: "product",
                    name: "Product",
                    component: Product
                },
                {
                    path: "purchase_price",
                    name: "PurchasePrice",
                    component: PurchasePrice
                },
                {
                    path: "selling_price",
                    name: "SellingPrice",
                    component: SellingPrice
                },
                {
                    path: "contract",
                    component: Contract,
                    children: [
                        {
                            path: "",
                            name: "ListContract",
                            component: ListContract
                        },
                        {
                            path: "create",
                            name: "CreateContract",
                            component: CreateContract
                        },
                        {
                            path: ":id",
                            name: "ViewContract",
                            component: ViewContract
                        },
                        {
                            path: ":id/edit",
                            name: "CreateContract",
                            component: CreateContract
                        }
                    ]
                }

                // {
                //     path: "typography",
                //     name: "TypographyPage",
                //     component: TypographyPage
                // },
                // {
                //     path: "components/icons",
                //     name: "IconsPage",
                //     component: IconsPage
                // },
                // {
                //     path: "notifications",
                //     name: "NotificationsPage",
                //     component: NotificationsPage
                // },
                // {
                //     path: "components/charts",
                //     name: "ChartsPage",
                //     component: ChartsPage
                // },
                // {
                //     path: "tables",
                //     name: "TablesBasicPage",
                //     component: TablesBasicPage
                // },
                // {
                //     path: "components/maps",
                //     name: "GoogleMapPage",
                //     component: GoogleMapPage
                // }
            ]
        }
    ]
});
