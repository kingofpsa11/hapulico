<template>
    <router-view />
</template>

<script>
export default {
    name: "App",
    created() {
        const currentPath = this.$router.history.current.path;
        if (currentPath === "/" || currentPath === "/app") {
            this.$router.push("/app/dashboard");
        }
    }
};
</script>

<style src="../sass/theme.scss" lang="scss" />
