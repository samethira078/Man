<template>
    <div>
        <v-container class="mt-12">
            <v-row>
                <v-col sm="10" offset-sm="2" xl="6" offset-xl="3">
                    <h1 class="mb-5">PAARDEN</h1>
                    <v-simple-table fixed-header>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th class="text-left">
                                    Id
                                </th>
                                <th class="text-left">

                                </th>
                                <th class="text-left">
                                    Paard
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <!--Loop langs paarden met key of verwarring te verkomen-->
                            <tr
                                v-for="item in $store.state.horses"
                                :key="item.id"
                            >
                                <td>{{ item.id }}</td>
                                <td><img :src="require('@/assets/paarden/' + item.img)" height="50" width="50"
                                         style="margin-top: 5px">
                                </td>
                                <td>{{ item.naam }}</td>
                                <div class="text-right">
                                    <!--Stuurt de gegevens door om bij bewerking te gebruiken-->
                                    <v-btn
                                        @click.native="editItem(item.id, item.naam, item.bio, item.leeftijd, item.type)"
                                        text color="green">Bewerken
                                    </v-btn>
                                    <v-btn @click="removeItem(item.id)" text color="red">Verwijderen</v-btn>
                                </div>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-container>
        <template>
            <v-overlay opacity="0.8" v-if="order" :value="order">
                <v-card
                    class="mx-auto">
                    <v-card-text>
                        <!--Gebruikt bewerking uit editItem methode-->
                        <p class="text-h4">
                            Bewerking #{{ horse.naam }}
                        </p>
                    </v-card-text>
                    <v-form name='imageForm' enctype="multipart/form-data" ref="form" v-model="valid" class="mt-5 mx-5 px-4">
                        <!--Validatie-->
                        <div>
                            <v-text-field
                                v-model="horse.name"
                                name="name"
                                :counter="12"
                                :rules="[
                                   v => !!v || 'Naam is verplicht',
                                   v => /^[a-zA-Z\s]*$/.test(v) || 'Alleen letters',
                                   ]"
                                label="Naam van paard"
                                required
                            ></v-text-field>
                            <v-alert v-if="name_error" v-show="name_error" elevation="7" class="hidden" type="error">Naam in gebruik
                            </v-alert>
                            <v-text-field
                                v-model="horse.age"
                                :counter="2"
                                :rules="[
                                   v => !!v || 'Leeftijd is verplicht',
                                   v => /^[0-9]/.test(v) || 'Alleen getallen',
                                   v => (v && v.length <= 2) || 'Max twee getallen',
                                   ]"
                                label="Leeftijd"
                                required
                            ></v-text-field>
                            <v-select
                                v-model="horse.race"
                                :items="items"
                                :rules="[v => !!v || 'Item is required']"
                                label="Soort"
                                required
                            ></v-select>
                            <v-text-field
                                v-model="horse.bio"
                                :counter="100"
                                :rules="[
                                   v => !!v || 'Bio is verplicht',
                                   v => (v && v.length <= 100) || 'Max 100 letters',
                                   ]"
                                label="Bio"
                                required
                            ></v-text-field>
                        </div>
                    </v-form>
                    <v-card-actions class="justify-center">
                        <v-btn @click="pushOrder()" text class="mx-8 mb-3" color="green">Bewerken</v-btn>
                        <v-btn @click="cancelOrder()" text class="mx-8 mb-3" color="red">Annuleren</v-btn>
                    </v-card-actions>
                </v-card>
            </v-overlay>
        </template>
    </div>
</template>

<script>
export default {
    name: "EditH",
    data() {
        return {
            items: [
                'Paard',
                'Pony',
            ],
            valid: false,
            order: false,
            name_error: false,
            horse: [
                {
                    id: ''
                },
                {
                    name: ''
                },
                {
                    bio: ''
                },
                {
                    age: ''
                },
                {
                    race: ''
                }
            ]
        }
    },
    //Haalt de paarden op
    methods: {
        loadHorses() {
            return this.$store.dispatch('grabHorses');
        },
        //Verwijderd een paard
        removeItem(horse) {
            if (confirm("Wil je het zeker verwijderen?")) {
                return this.$store.dispatch('removeHorse', horse).then(() => {
                    this.loadHorses();
                });
            }
        },
        //Haalt de gegevens op om te bewerken
        editItem(id, naam, bio, leeftijd, type) {
            this.horse.id = id;
            this.horse.name = naam;
            this.horse.bio = bio;
            this.horse.age = leeftijd;
            this.horse.type = type;
            this.order = true;
        },
        //Reset velden
        cancelOrder() {
            this.order = false;
            this.name_error = false;
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
        },
        //Verstuur request
        pushOrder(){
            this.$refs.form.validate();
            if(this.valid){
                //valid
                return this.$store.dispatch('updateSelectedHorse', this.horse).then(()=>{
                    this.cancelOrder();
                    this.loadHorses();
                //    error
                }).catch(() => {
                    this.name_error = true;
                });
            }
        }
    },
    beforeMount() {
        this.loadHorses();
    }
}
</script>

<style scoped>

</style>
