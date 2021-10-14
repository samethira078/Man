<template>
    <v-container class="mt-12">
        <v-row>
            <v-col sm="10" offset-sm="2" xl="6" offset-xl="3">
                <h1 class="mb-5">AANKOPEN</h1>
                <!--validatie-->
                <v-simple-table fixed-header>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th class="text-left">
                                Id
                            </th>
                            <th class="text-left">
                                Uniekcode
                            </th>
                            <th class="text-left">
                                Datum
                            </th>
                            <th class="text-left">
                                Tijd
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!--loop door gebruikers-->
                        <tr
                            v-for="item in allOrders"
                            :key="item.id"
                        >
                            <td>{{ item.email }}</td>
                            <td>#{{ item.unique }}</td>
                            <td>{{ item.datum }}</td>
                            <td>{{ item.tijd }}</td>
                            <div class="text-right">
                                <!--pak de specifieke gebruiker informatie-->
                                <v-btn
                                    @click.native="editItem(item.id, item.fullname, item.adres, item.nummer, item.datum, item.tijd)"
                                    text color="green">
                                    Bewerken
                                </v-btn>
                                <v-btn @click="removeItem(item.id)" text color="red">Verwijderen</v-btn>
                            </div>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-col>
        </v-row>
        <template>
            <template>
                <!--  Overlay van gewenste paard dat bewerkt met worden-->
                <v-overlay opacity="0.8" v-if="order" :value="order">
                    <v-card
                        class="mx-auto">
                        <v-card-text>
                            <p class="text-h4">
                                Bewerking #{{ user.id }}
                            </p>
                        </v-card-text>
                        <v-form class="mt-5 mx-5 px-4" ref="form" v-model="valid">
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-text-field
                                        v-model="user.fullname"
                                        :rules="[
                                           v => !!v || 'Veld is leeg',
                                           v => /^[a-zA-Z ]*$/i.test(v) || 'Geen geldig naam',
                                           ]"
                                        label="Volledige naam"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="12">
                                    <v-text-field
                                        v-model="user.adres"
                                        :rules="[
                                           v => !!v || 'Veld is leeg',
                                           v => /^([1-9][e][\s])*([a-zA-Z]+(([\][\s])|([\s]))?)+[1-9][0-9]*(([-][1-9][0-9]*)|([\s]?[a-zA-Z]+))? (?:NL-)?(\d{4})\s*([A-Z]{2}) [a-zA-Z]*$/i.test(v) || '[Straat + huisnummer + postcode + woonplaats]',
                                           ]"
                                        label="Volledige adres"
                                        required
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <div>
                                <v-menu
                                    ref="menu"
                                    v-model="activePicker"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field @click="getDates()" v-model="date" label="Datum" readonly
                                                      v-bind="attrs"
                                                      v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <!--  Pak datum erbij als keuze-->
                                    <v-date-picker v-model="date" :active-picker.sync="activePicker"
                                                   :min="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                                                   @change="save"
                                    ></v-date-picker>
                                </v-menu>
                                <div v-if="date" v-show="date">
                                    <v-select :rules="[v => !!v || 'Veld is verplicht']" v-if="tijden.length" v-show="tijden.length" :items="tijden" v-model="tijd"
                                              label="Beschikbare tijden"></v-select>
                                </div>
                            </div>
                        </v-form>
                        <v-card-actions class="justify-center">
                            <v-btn @click="pushOrder()" text class="mx-8 mb-3" color="green">Bewerken</v-btn>
                            <v-btn @click="cancelOrder()" text class="mx-8 mb-3" color="red">Annuleren</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-overlay>
            </template>
        </template>
    </v-container>
</template>

<script>
export default {
    name: "List",
    data() {
        return {
            order: false,
            valid: false,
            user: [
                {
                    id: ''
                },
                {
                    fullname: ''
                },
                {
                    adres: ''
                },
                {
                    nummer: ''
                },
                {
                    datum: ''
                },
                {
                    tijd: ''
                },
            ],
            activePicker: null,
            validNum: null,
            phoneCheck: null,
            tijden: [],
            date: null,
            tijd: null,
            allOrders: [],
        }
    },
    watch: {
        validNum(newVal) {
            if (newVal.isValid) {
                this.phoneCheck = newVal.isValid;
            } else {
                this.phoneCheck = false;
            }
        },
        date(newVal) {
            if (newVal) {
                this.user.datum = newVal;
                this.$store.dispatch('getDates', this.user).then(response => {
                    console.log(response);
                    this.tijden = [];
                    this.tijden = response;
                })
            }
        },
        tijd(newVal) {
            if (newVal) {
                this.user.tijd = newVal;
            }
        }
    },
    methods: {
        cancelOrder() {
            this.order = false;
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
            this.grabUsers();
        },
        //Request
        pushOrder(){
            this.$refs.form.validate();
            if(this.valid){
                return this.$store.dispatch('updateSelectedOrder', this.user).then(()=>{
                    this.cancelOrder();
                    this.grabUsers();
                    //    error
                }).catch(() => {

                });
            }
        },
        removeItem(horse) {
            if (confirm("Wil je het zeker verwijderen?")) {
                return this.$store.dispatch('removeOrder', horse).then(() => {
                    console.log(horse);
                    this.grabUsers();
                });
            }
        },
        grabUsers() {
            return this.$store.dispatch('allOrders').then(response => {
                this.allOrders = response.data;
            });
        },
        editItem(id, fullname, adres, nummer, datum, tijd) {
            this.user.id = id;
            this.user.fullname = fullname;
            this.user.adres = adres;
            this.user.datum = datum;
            this.user.tijd = tijd;
            nummer = null;
            this.order = true;
        },
    },
    mounted() {
        this.grabUsers();
    }
}
</script>

<style scoped>

</style>
