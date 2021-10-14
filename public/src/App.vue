<template>
  <v-app>
    <v-main class="grey lighten-3">
      <Navbar/>
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>
import Navbar from "./views/Navbar";

export default {
  components: {
    Navbar,
  },
  name: 'App',

  data: () => ({
    //
  }),
};
</script>
