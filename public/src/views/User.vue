<template>
  <div>
    <v-container class="mt-1">
      <v-row v-if="$store.state.Horses.paid_horses">
        <v-col v-for="horse in $store.state.Horses.paid_horses" :key="horse.id" cols="12" md="4" xl="3" sm="6">
          <v-card class="mx-auto" max-width="400">
            <v-card-title>
              <span class="mr-2">Paard: {{ horse.naam }}</span>
              <v-img v-if="horse.img"
                  :src="require('@/assets/paarden/' + horse.img)"
                  max-width="30"
              ></v-img>
            </v-card-title>
            <v-card-text>
              <p><b>Naam:</b> {{ horse.fullname }}</p>
              <p><b>Adres:</b> {{ horse.adres }}</p>
              <p><b>Telefoonnummer:</b> {{ horse.nummer }}</p>
              <p><b>Datum:</b> {{ horse.datum }}</p>
              <p><b>Tijd:</b> {{ horse.tijd }}</p>
              <p><b>Unique code:</b> #{{ horse.unique }}</p>
              <p><b>Status</b> Betaald</p>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "User",
  data() {
    return {
      horses: this.$store.state.Horses.paid_horses,
    }
  },
  methods: {
    orders() {
      return [
        this.$store.dispatch('paidHorses')
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
