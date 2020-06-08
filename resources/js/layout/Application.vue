<template>
    <div>
        <v-navigation-drawer v-model="drawer" app clipped>
            <v-list nav>
                <v-list-item :to="{ name: 'main' }" exact>
                    <v-list-item-action>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Главная</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item :to="{ name: 'users' }">
                    <v-list-item-action>
                        <v-icon>mdi-contact-phone-outline</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Users</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app clipped-left>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>App</v-toolbar-title>
        </v-app-bar>
        <v-content>
            <v-container class="pt-2" fluid fill-height>
                <v-layout column>
                    <v-flex>
                        <router-view></router-view>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <v-footer app>
            <span>&copy; 2020 {{ $t('yes') }}</span>
        </v-footer>
    </div>
</template>

<script>
export default {
    props: {
        source: String
    },
    data: () => ({
        drawer: null
    })
}
</script>
