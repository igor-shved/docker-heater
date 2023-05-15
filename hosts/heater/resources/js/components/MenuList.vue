<template>
    <header class="header">
        <nav>
            <ul class="menu__list">
                <template v-for="(link_item, index) in menu_link"
                          :key="index">
                    <a :href="link_item.link">
                        <li>{{ link_item.title }}</li>
                    </a>
                </template>
            </ul>
        </nav>
    </header>
</template>

<script>
export default {
    name: "menu_block",
    data() {
        return {
            menu_link: [
                {
                    title: "Управление домом",
                    link: "/"
                },
                {
                    title: "Debug heater",
                    link: "/debug"
                }
            ]
        }
    }
}
</script>

<style scoped>

</style>
