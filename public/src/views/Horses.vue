<template>
    <div>
        <v-container class="mt-1">
            <v-alert v-if="booking" v-show="booking"
                     prominent
                     type="success"
            >
                <v-row align="center">
                    <v-col class="grow">
                        {{
                            booking
                        }}
                    </v-col>
                    <v-col class="shrink">
                        <v-btn router to="/orders">Bestellingen</v-btn>
                    </v-col>
                </v-row>
            </v-alert>
            <v-row>
                <!--Haalt de paarden op na mounted Ajax request-->
                <v-col v-for="horse in $store.state.horses" :key="horse.id" cols="12" md="4" xl="3" sm="6">
                    <v-card class="mx-auto my-12">
                        <v-img v-if="horse.img"
                            :src="require('@/assets/paarden/' + horse.img)"
                            height="200"
                        ></v-img>
                        <v-card-title>{{ horse.naam }}</v-card-title>
                        <v-card-text>
                            <v-row align="center" class="mx-0"></v-row>
                            <div class="my-4">
                            </div>
                            <div>
                                Type: <b>{{ horse.type }}</b><br>
                                Leeftijd: <b>{{ horse.leeftijd }}</b>
                            </div>
                        </v-card-text>
                        <v-card-subtitle>
                            {{ horse.bio }}
                        </v-card-subtitle>
                        <v-divider class="mx-4"></v-divider>
                        <!--AUTH-->
                        <v-card-actions v-if="token != null">
                            <!--Gewenste paard gegevens ophalen-->
                            <v-btn @click="orderHorse(horse.naam, horse.img, horse.id)" text color="green"
                                   class="text--darken-4">
                                Reseveren
                            </v-btn>
                        </v-card-actions>
                        <v-card-actions v-else>
                            <!--Auth mislukt-->
                            <v-btn router to="/login" text color="green" class="text--darken-4">Reseveren</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <template>
            <!--Toevoegen van gewenste paard-->
            <v-overlay opacity="0.8" :value="order">
                <v-card
                    class="mx-auto">
                    <v-card-text>
                        <p class="text-h4">
                            <!--Gegevens van gewenste paard-->
                            Bestelling: {{ selected.naam }}
                        </p>
                        <v-img v-if="order"
                               width="350"
                               :src="require('@/assets/paarden/' + selected.img)"
                               height="200"
                        ></v-img>
                    </v-card-text>
                    <!--Pak huldige gebruiker e-mail-->
                    <v-form class=" mx-5 px-4">
                        <v-text-field
                            label="E-mail"
                            readonly
                            :value="user_data.name"
                            required
                        ></v-text-field>
                        <div>
                            <v-menu
                                ref="menu"
                                v-model="activePicker"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <!--Bij kiezen van datum, stuur ajax request met beschikbare tijden-->
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field @click="getDates()" v-model="date" label="Datum" readonly
                                                  v-bind="attrs"
                                                  v-on="on"></v-text-field>
                                </template>
                                <!--Min datum: vandaag-->
                                <v-date-picker v-model="date" :active-picker.sync="activePicker"
                                               :min="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                                               @change="save"
                                ></v-date-picker>
                            </v-menu>
                            <v-alert v-if="errors.date" v-show="errors.date" dense outlined type="error">
                                {{ errors.date[0] }}
                            </v-alert>
                            <!--Promise van ajax met beschikbare tijden-->
                            <div v-show="date">
                                <v-select v-show="tijden.length" :items="tijden" v-model="tijd"
                                          label="Beschikbare tijden"></v-select>
                                <!--Volgeboekt geen tijden-->
                                <v-alert v-show="!tijden.length" elevation="7" class="hidden" type="error">Volgeboekt.
                                    Kies een ander
                                    datum
                                </v-alert>
                            </div>
                            <v-alert v-if="errors.tijd && tijden.length" v-show="errors.tijd" dense outlined
                                     type="error">
                                {{ errors.tijd[0] }}
                            </v-alert>
                        </div>
                    </v-form>
                    <!--Stuur request om paard te reseveren of annuleer-->
                    <v-card-actions class="justify-center">
                        <v-btn @click="pushOrder()" text class="mx-8 mb-3" color="green">Bestellen</v-btn>
                        <v-btn @click="cancelOrder()" text class="mx-8 mb-3" color="red">Annuleren</v-btn>
                    </v-card-actions>
                </v-card>
            </v-overlay>

        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            activePicker: null,
            date: null,
            tijden: [''],
            srcs: '1633578707.png',
            // number: '',
            // adres: '',
            tijd: '',
            booking: '',
            selected: [
                {
                    naam: ''
                },
                {
                    img: ''
                },
                {
                    paard_id: ''
                },
                {
                    date: ''
                }
            ],
            errors: [],
            order: false,
            user_data: [],
        }
    },
    //Monitoort of er een wijziging is in de datum
    watch: {
        date(newVal) {
            if (newVal) {
                this.selected.date = newVal;
                this.$store.dispatch('getDates', this.selected).then(response => {
                    this.tijden = [];
                    this.tijden = response;
                })
            }
        }
    },
    //Haalt token van gebruiker erbij om auth te controleren
    computed: {
        token() {
            return localStorage.getItem('token')
        }
    },
    methods: {
        pushOrder() {
            //Nieuwe array met gegevens die moeten worden verzonden naar request
            let order = {
                number: this.number,
                adres: this.adres,
                date: this.date,
                tijd: this.tijd,
                paard_id: this.selected.paard_id
            }
            //Request                                               //valid
            return this.$store.dispatch('checkOrder', order).then(() => {
                this.booking = 'Je paard is successvol in je mandje toegevoegd.';
                this.errors = [];
                this.cancelOrder();
                //fail met errors
            }).catch(errors => {
                this.errors = errors.response.data.errors || {};
            });
        },
        //Reset gegevens
        cancelOrder() {
            this.order = false;
            this.activePicker = null;
            this.date = null;
            this.errors = [];
            this.tijden = [''];
            this.number = '';
            this.adres = '';
            this.tijd = '';
        },
        //Overlay gebruiken om te reseveren
        orderHorse(id, naam, real_id) {
            this.selected.naam = id;
            this.selected.img = naam;
            this.selected.paard_id = real_id;

            this.order = true;
            this.$store.dispatch('getUserInfo').then(response => {
                this.user_data = response.data
            }).catch(() => {
                //Uitloggen als auth mislukt
                this.$router.push('/logout')
            })
        },
        loadHorses() {
            return this.$store.dispatch('grabHorses');
        }
    },
    beforeMount() {
        this.loadHorses();
    }
}
</script>

<style scoped>

</style>
