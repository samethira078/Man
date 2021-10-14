<template>
    <v-container class="mt-16">
        <v-row class="justify-center">
            <v-col cols="12" md="8" xl="5" sm="7" class="jus">
                <v-card>
                    <v-card-title class="justify-center">
            <span class="text-h4">
            Registeren</span>
                    </v-card-title>
                    <v-card-text>
                        <v-alert v-show="log.message != ''" elevation="7" class="hidden" :type="log.type">{{
                                log.message
                            }}
                        </v-alert>
                        <v-form name='imageForm' enctype="multipart/form-data" ref="form" v-model="valid"
                                class="mt-5 mx-5 px-4">
                            <v-text-field
                                v-model="form.username"
                                name="name"
                                type="email"
                                :rules="[
                                   v => !!v || 'E-mail is verplicht',
                                   v => /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(v) || 'Geen geldig e-mail',
                                   ]"
                                label="E-mail"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="form.password"
                                type="password"
                                name="password"
                                :rules="[
                                   v => !!v || 'Wachtwoord is verplicht',
                                   v => (v && v.length > 8) || 'Wachtwoord te kort',
                                   ]"
                                label="Wachtwoord"
                                required
                            ></v-text-field>
                            <v-btn color="orange" @click="RequestLogin">
                                Creëren
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Register",
    data() {
        return {
            valid: false,
            form: {
                username: '',
                password: '',
            },
            log: {
                type: '',
                message: '',
            }
        }
    },
    //Registratie validatie
    methods:{
        RequestLogin(){
            if(this.valid) {

                return this.$store.dispatch('RegisterForm', this.form).then(() => {
                    this.log.type = 'success';
                    this.log.message = 'Aangemaakt! Je wordt verstuurd om in te loggen!'
                    setTimeout(() => window.location.href = "/login", 3000);
                }).catch(response => {
                    console.log(response);
                    this.log.type = 'error';
                    this.log.message = 'E-mail in gebruik'
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
