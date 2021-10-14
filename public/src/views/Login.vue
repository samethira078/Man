<template>
  <v-container class="mt-16">
    <v-row class="justify-center">
      <v-col cols="12" md="8" xl="5" sm="7" class="jus">
        <v-card>
          <v-card-title class="justify-center">
            <span class="text-h4">
            Inloggen</span>
          </v-card-title>
          <v-card-text>
            <v-alert v-show="log.message != ''"  elevation="7" class="hidden" :type="log.type">{{ log.message }}</v-alert>
            <form @submit.prevent="login">
              <v-text-field name="name" v-model="form.username" label="Gebruikersnaam"></v-text-field>
              <v-text-field name="pass" v-model="form.password" type="password" label="Wachtwoord"></v-text-field>
              <button>
                <v-btn color="orange" v-show="true" @click.prevent="RequestLogin(form)">
                  Inloggen
                </v-btn>
              </button>
            </form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: "Login",
  data() {
    return {
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
  methods: {
    RequestLogin(data) {
      //  Login request
      this.$store.dispatch('LoginForm', data).then(() => {
        //  success
        this.$store.dispatch('getUserInfo');
        this.log.type = 'success';
        this.log.message = 'Ingelogd! Je wordt verstuurd.'
        setTimeout( () => window.location.href="/", 3000);
      //  Error
      }).catch(() => {
        this.log.type = 'error';
        this.log.message = 'Login mislukt.';
      })
    }
  }
}
</script>
