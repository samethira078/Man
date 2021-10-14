<template>
    <v-container class="mt-12">
        <v-row>
            <v-col sm="10" offset-sm="2" xl="6" offset-xl="3">
                <h1 class="mb-5">GEBRUIKERS</h1>
                <!--validatie-->
                <v-simple-table fixed-header>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th class="text-left">
                                Id
                            </th>
                            <th class="text-left">
                                Email
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!--loop door gebruikers-->
                        <tr
                            v-for="item in users"
                            :key="item.id"
                        >
                            <td>{{ item.id }}</td>
                            <td>{{ item.email }}</td>
                            <div class="text-right">
                                <!--pak de specifieke gebruiker informatie-->
                                <v-btn @click.native="editItem(item.id, item.rank, item.email)" text color="green">
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
            <v-overlay opacity="0.8" v-if="order" :value="order">
                <v-card
                    class="mx-auto">
                    <v-card-text>
                        <p class="text-h4">
                            <!--Gebruik info uit editItem method-->
                            Bewerking #{{ user.id }}
                        </p>
                    </v-card-text>
                    <v-form name='imageForm' enctype="multipart/form-data" ref="form" v-model="valid"
                            class="mt-5 mx-5 px-4">
                        <div>
                            <!--validatie-->
                            <v-text-field
                                v-model="user.email"
                                :counter="100"
                                :rules="[
                                   v => !!v || 'Email',
                                   v => (v && v.length <= 100) || 'Max 100 letters',
                                   v => /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(v) || 'Geen geldig e-mail',
                                   ]"
                                label="E-mail"
                            ></v-text-field>
                            <v-select
                                v-model="user.rank"
                                :items="items"
                                :rules="[v => !!v || 'Rank is verplicht']"
                                label="Rank"
                                required
                            ></v-select>
                        </div>
                    </v-form>
                    <v-card-actions class="justify-center">
                        <v-btn @click="pushOrder()" text class="mx-8 mb-3" color="green">Bewerken</v-btn>
                        <v-btn @click="cancelOrder()" text class="mx-8 mb-3" color="red">Annuleren</v-btn>
                    </v-card-actions>
                </v-card>
            </v-overlay>
        </template>
    </v-container>
</template>

<script>
export default {
    name: "Gebruikers",
    data() {
        return {
            order: false,
            items: [
                '1',
                '2',
            ],
            users: [],
            valid: false,
            user: [
                {
                    id: ''
                },
                {
                    rank: ''
                },
                {
                    email: ''
                },
            ]
        }
    },
    methods: {
        //Pak de gebruikers
        grabUsers(){
            return this.$store.dispatch('userList').then(response => {
                this.users = response.data;
            });
        },
        //Voeg specifieke informatie (ligt aan button) toe om te beweren
        editItem(id, rank, mail) {
            this.user.id = id;
            this.user.rank = rank;
            this.user.email = mail;
            this.order = true;
        },
        //Reset
        cancelOrder(){
            this.order = false;
            this.name_error = false;
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
        },
        //Request
        pushOrder(){
            this.$refs.form.validate();
            if(this.valid){
                //valid
                return this.$store.dispatch('updateSelectedUser', this.user).then(()=>{
                    this.cancelOrder();
                    this.grabUsers();
                //    error
                }).catch(() => {
                    this.name_error = true;
                });
            }
        },
        //Verwijder item
        removeItem(horse) {
            if (confirm("Wil je het zeker verwijderen?")) {
                return this.$store.dispatch('removeUser', horse).then(() => {
                    this.grabUsers();
                });
            }
        },
    },
    mounted() {
       this.grabUsers();
    }
}
</script>

<style scoped>

</style>
