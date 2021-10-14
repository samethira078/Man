<template>
    <nav>
        <v-app-bar text>
            <v-btn @click="nav_button" plain small text>
                <v-icon v-if="drawer">mdi-close</v-icon>
                <v-icon v-else>mdi-menu</v-icon>
            </v-btn>
            <v-app-bar-title class="ml-4 mr-1 text-uppercase green--text text--darken-3">
                <span class="font-weight-light">Man</span>
                <span>ege</span>
            </v-app-bar-title>
            <v-img class="" src="../img/logo.png"
                   max-height="50px"
                   max-width="50px"
            ></v-img>
            <v-spacer></v-spacer>
            <v-btn text small depressed class="white--text " dark color="blue">
                <v-icon small>fab fa-facebook</v-icon>
            </v-btn>
            <v-btn text small depressed class="white--text " dark color="green">
                <v-icon small>fab fa-whatsapp</v-icon>
            </v-btn>
            <v-btn text small depressed class="white--text" dark color="red">
                <v-icon small>fab fa-youtube</v-icon>
            </v-btn>
        </v-app-bar>
        <v-navigation-drawer v-model="drawer" app class="green darken-4">
            <v-list>

                <v-list-item @click="routeTo('Home')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Home</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="routeTo('Horses')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-horse-head</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Paarden</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="!loggedIn && !admin" @click="routeTo('Login')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-user</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Inloggen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="!loggedIn && !admin" @click="routeTo('Register')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-user</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Registeren</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="loggedIn && !admin" @click="routeTo('Orders')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-shopping-basket</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Bestellingen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="loggedIn && !admin" @click="routeTo('User')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-house-user</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Mijn paarden</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="admin" @click="routeTo('Add')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-cog</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Paard toevoegen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="admin" @click="routeTo('EditH')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-cog</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Paarden bewerken</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="admin" @click="routeTo('Gebruikers')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-cog</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Gebruikers</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="admin" @click="routeTo('List')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-cog</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Overzicht aankopen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="loggedIn" @click="routeTo('Logout')">
                    <v-list-item-action>
                        <v-icon class="white--text">fas fa-sign-out-alt</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="white--text">Uitloggen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>
    </nav>
</template>

<script>
export default {
    name: "Navbar",
    data() {
        return {
            drawer: true,
            admin: false,
            links: [
                {
                    icon: '', text: 'Homepagina', route: '/'
                },
                {
                    icon: 'mdi-account', text: 'Paarden', route: '/horses'
                },
                {
                    icon: 'mdi-view-dashboard', text: 'Account', route: '/login'
                },
            ],
        }
    },
    methods: {
        nav_button() {
            this.drawer = !this.drawer
        },
        routeTo(val) {
            this.$router.push({name: val})
        },
        //Routes met admin
        isAdmin() {
            return this.$store.dispatch('getUserInfo').then(response => {
                if (response.data['rank'] == 1) {
                    this.admin = true;
                }
            }).catch(() => {

            });
        }
    },
    computed: {
        //Routes met auth token
        loggedIn() {
            return this.$store.getters.loggedIn;
        },
    },
    beforeMount() {
        this.isAdmin();
    }

}
</script>

<style scoped>

</style>
