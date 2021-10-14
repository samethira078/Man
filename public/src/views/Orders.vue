<template>
  <div>
    <v-container v-if="unpaid" v-show="unpaid != ''" class="mt-12">
      <v-row>
        <v-col sm="10" offset-sm="2" xl="6" offset-xl="3">
          <h1 class="mb-5">MANDJE</h1>
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
                <th class="text-left">
                  Datum
                </th>
                <th class="text-left">
                  Tijd
                </th>
              </tr>
              </thead>
              <tbody>
              <!--Pak alle bestellingen die niet betaald zijn-->
              <tr
                  v-for="item in unpaid"
                  :key="item.id"
              >
                <td>{{ item.id }}</td>
                <td><img :src="require('@/assets/paarden/' + item.img)" height="50" width="50" style="margin-top: 5px">
                </td>
                <td>{{ item.naam }}</td>
                <td>{{ item.datum }}</td>
                <td>{{ item.tijd }}</td>
                <div class="text-right">
                  <!--  Stuur gewenste item mee die bewerkt moet worden-->
                  <v-btn @click.native="editItem(item.id, item.img, item.paard_id)" text color="green">Bewerken
                  </v-btn>
                  <!--  Verwijderen-->
                  <v-btn @click="removeItem(item.id)" text color="red">Verwijderen</v-btn>
                </div>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="10" offset-sm="2" xl="6" offset-xl="3">
          <v-alert
              border="right"
              colored-border
              elevation="2"
          >Hoeveelheid: <b>{{ unpaid.length }}</b> - Totaalprijs: <b>€{{ unpaid.length * 55 }}</b>
          </v-alert>
        </v-col>
      </v-row>
      <v-row>
        <!--  BESTELLEN-->
        <v-col sm="10" offset-sm="2" xl="6" offset-xl="3">
          <h1 class="mb-5">BESTELLEN</h1>
          <!--  Validatie-->
          <v-form ref="form" v-model="valid" class="white px-12">
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                    v-model="form.first_name"
                    :rules="firstNameRules"
                    label="Voornaam"
                    required
                ></v-text-field>
              </v-col>
              <v-spacer></v-spacer>
              <v-col cols="12" md="4">
                <v-text-field
                    v-model="form.sur_name"
                    :rules="surNameRules"
                    label="Achternaam"
                    required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                    v-model="form.adres"
                    :rules="adresRules"
                    label="Adres"
                    required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                    v-model="form.postcode"
                    :rules="zipRules"
                    label="Postcode"
                    required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                    v-model="form.woonplaats"
                    :rules="cityRules"
                    label="Woonplaats"
                    required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="12">
                <!--  Plugin https://www.npmjs.com/package/vue-phone-number-input -->
                <p class="red--text mt-1" v-show="error_time" v-if="error_time">{{ error_time}}</p>
                <vue-phone-number-input v-model="form.number"
                                        :only-countries="['NL', 'BE']"
                                        :translations="{
                                              countrySelectorLabel: 'Land Code',
                                              countrySelectorError: 'Kies een land',
                                              phoneNumberLabel: 'Telefoonnr.',
                                              example: 'Bijvoorbeeld:'
                                         }"
                                        size="sm"
                                        @update="validNum = $event"
                >
                </vue-phone-number-input>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="text-right">
                <v-btn :disabled="!valid " @click="submitOrder()" text color="green">Bestellen</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
    <v-container v-show="unpaid == '' && !success">
      <h1>Je mand is leeg!</h1><v-btn color="green" route to="/horses">Klik hier om te bestellen</v-btn>
    </v-container>
    <v-container v-show="success">
      <h1>Je bestelling is voltooid. Bekijk je overzicht</h1><v-btn color="green" route to="/user">Mijn paarden</v-btn>
    </v-container>
    <template>
      <!--  Overlay van gewenste paard dat bewerkt met worden-->
      <v-overlay opacity="0.8" v-if="order" :value="order">
        <v-card
            class="mx-auto">
          <v-card-text>
            <p class="text-h4">
              Bewerking #{{ details.id }}
            </p>
          </v-card-text>
          <v-img :src="require('@/assets/paarden/' + details.img)" height="200"
          ></v-img>
          <v-form class="mt-5 mx-5 px-4">
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
                  <v-text-field @click="getDates()" v-model="date" label="Datum" readonly v-bind="attrs"
                                v-on="on"></v-text-field>
                </template>
                <!--  Pak datum erbij als keuze-->
                <v-date-picker v-model="date" :active-picker.sync="activePicker"
                               :min="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                               @change="save"
                ></v-date-picker>
              </v-menu>
              <v-alert v-if="errors_date.date" v-show="true" dense outlined type="error">{{ errors_date.date[0] }}
              </v-alert>
              <div v-if="date" v-show="date">
                <v-select v-if="tijden.length" v-show="tijden.length" :items="tijden" v-model="tijd" label="Beschikbare tijden"></v-select>
                <v-alert v-if="!tijden.length" v-show="!tijden.length" elevation="7" class="hidden" type="error">Volgeboekt. Kies een ander
                  datum
                </v-alert>
              </div>
              <v-alert v-if="errors_date.tijd" v-show="true" dense outlined type="error">
                {{ errors_date.tijd[0] }}
              </v-alert>
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
  name: "Orders",
  data() {
    return {
      unpaid: [],
      activePicker: null,
      date: null,
      nummer: '',
      tijden: [],
      validNum: null,
      phoneCheck: null,
      form: [
        {
          first_name: '',
        },
        {
          sur_name: '',
        },
        {
          adres: '',
        },
        {
          postcode: '',
        },
        {
          woonplaats: '',
        },
        {
          number: '',
        }
      ],
      valid: false,
      tijd: '',
      details: [
        {
          id: ''
        },
        {
          img: ''
        },
        {
          date: ''
        },
        {
          tijd: ''
        },
        {
          paard_id: ''
        }
      ],
      order: false,
      success: false,
      error_time: '',
      errors_date: '',
      firstNameRules: [
        v => !!v || 'Veld is leeg',
        v => /^[a-zA-Z]*$/i.test(v) || 'Geen geldig naam',
      ],
      surNameRules: [
        v => !!v || 'Veld is leeg',
        v => /^[a-zA-Z]*$/i.test(v) || 'Geen geldig achternaam',
      ],
      adresRules: [
        v => !!v || 'Veld is leeg',
        v => /^([1-9][e][\s])*([a-zA-Z]+(([\][\s])|([\s]))?)+[1-9][0-9]*(([-][1-9][0-9]*)|([\s]?[a-zA-Z]+))?$/i.test(v) || 'Geen geldig adres',
      ],
      zipRules: [
        v => !!v || 'Veld is leeg',
        v => /^(?:NL-)?(\d{4})\s*([A-Z]{2})$/i.test(v) || 'Geen geldig adres',
      ],
      cityRules: [
        v => !!v || 'Veld is leeg',
        v => /^[a-zA-Z]*$/i.test(v) || 'Geen geldig stad',
      ],
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
        this.details.date = newVal;
        this.$store.dispatch('getDates', this.details).then(response => {
          this.tijden = [];
          this.tijden = response;
        })
      }
    },
    tijd(newVal) {
      if (newVal) {
        this.details.tijd = newVal;
      }
    }
  },
  methods: {
    submitOrder() {
      if (!this.phoneCheck) {
        this.error_time = 'Voer een geldig nummer in';
      } else {
        this.error_time = null;
        let submit_order = [
          {
            "naam": this.form.first_name + ' ' + this.form.sur_name,
            "adres": this.form.adres + ' ' + this.form.postcode + ' ' + this.form.woonplaats,
            "horse": this.unpaid.map(s => s.id),
            "nummer": this.form.number,
          }
        ];
        return this.$store.dispatch('submitPayment', submit_order[0]).then(() => {
          this.success = true
          this.unpaid = [];
        }).catch(() => {
          this.$router.push('/logout')
        });
      }
    },
    cancelOrder() {
      this.order = false;
      this.details = [];
      this.date = null;
      this.activePicker = null;
      this.errors_date = [];
    },
    pushOrder() {
      return this.$store.dispatch('checkOrder', this.details).then(() => {
        this.orders();
        this.cancelOrder();
      }).catch(errors => {
        this.errors_date = errors.response.data.errors || {};
      });
    },
    removeItem(id) {
      if (confirm("Wil je het zeker verwijderen?")) {
        return this.$store.dispatch('removeOrder', id).then(() => {
          this.orders();
        });
      }
    },
    editItem(id, img, paard_id) {
      this.details.id = id;
      this.details.img = img;
      this.details.paard_id = paard_id;
      this.order = true
    },
    orders() {
      return [
        this.$store.dispatch('grabOrders').then(response => {
          this.unpaid = response.data;
        })
      ]
    }
  },
  beforeMount() {
    this.orders();
  }
}
</script>

<style scoped>

</style>
