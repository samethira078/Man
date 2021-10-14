<template>
  <div class="home">
    <v-container>
      <v-row>
        <v-col md="12" xl="8" lg="8" sm="12">
          <v-carousel hide-delimiters height="500" cycle>
            <v-carousel-item v-for="(slide,i) in slider" :key="i" :src="slide.src" class="" reverse-transition="fade-transition" transition="fade-transition">
              <v-row dark class="fill-height" align="end" >
                <div class="white--text font-weight-bold text-h2 ml-15 mb-5">
                  <h4 class="font-weight-bold">{{slide.text}}</h4>
                  <v-btn route :to="slide.route" :color="slide.color" x-large width="250" class="lighten-1 white--text font-weight-bold">{{ slide.button }}</v-btn>
                </div>
              </v-row>
            </v-carousel-item>
          </v-carousel>
        </v-col>
        <v-col md="12" xl="4" lg="4" sm="12">
          <v-row>
            <v-col md="6" xl="12" lg="12" sm="6" >
              <v-img height="250" src="../img/img.png">
                <v-row class="fill-height" align="center" justify="center">
                  <v-btn text color="white" x-large width="250" class="text-h3 lighten-1 white--text font-weight-bold">TARIEVEN</v-btn>
                </v-row>
              </v-img>
            </v-col>
            <v-col md="6" xl="12" lg="12" sm="6">
              <v-img height="225" src="../img/team.png">
                <v-row class="fill-height" align="center" justify="center">
                  <v-btn text color="white" x-large width="250" class="text-h3 lighten-1 white--text font-weight-bold">Het team</v-btn>
                </v-row>
              </v-img>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-banner single-line align="center" class="mt-5">
        <v-icon color="green" left>
          fas fa-check-square
        </v-icon>
        <span class="mr-5">
          Groot terrein
        </span>
        <v-icon color="green" left>
          fas fa-check-square
        </v-icon>
        <span class="mr-5">
          Vriendelijke paarden en pony's
        </span>
        <v-icon color="green" class="hidden-sm-and-down" left>
          fas fa-check-square
        </v-icon>
        <span class="mr-5 hidden-sm-and-down">
          Professionele instructeurs
        </span>
        <v-icon color="green" class="hidden-sm-and-down" left>
          fas fa-check-square
        </v-icon>
        <span class="mr-5 hidden-sm-and-down">
          Zelf opzadelen & borstelen
        </span>
      </v-banner>
      <v-row class="mt-4">
        <v-col md="4">
          <v-card dark class="green lighten-5">
            <v-card-title>
              <v-icon color="green" left>far fa-newspaper
              </v-icon><span class="font-weight-bold black--text">PAARDRIJDEN EEN SPORT?</span>
            </v-card-title>
            <v-card-text class="black--text">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci alias architecto, atque corporis ea eos est eveniet id inventore, maxime nesciunt nisi non obcaecati odio quam quas quasi sunt suscipit tempore veniam. Aperiam in itaque laudantium molestiae quis! Accusantium dignissimos eligendi enim optio voluptas? Consequatur enim fuga non totam voluptate.
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="orange">
                <span>Lees verder...</span>
                <v-icon></v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col xs="6" md="4">
          <v-card dark class="green lighten-5">
            <v-card-title>
              <v-icon color="green" left>far fa-newspaper
              </v-icon><span class="font-weight-bold black--text">BINNENKORT VERHUIZING</span>
            </v-card-title>
            <v-card-text class="black--text">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci alias architecto, atque corporis ea eos est eveniet id inventore, maxime nesciunt nisi non obcaecati odio quam quas quasi sunt suscipit tempore veniam. Aperiam in itaque laudantium molestiae quis! Accusantium dignissimos eligendi enim optio voluptas? Consequatur enim fuga non totam voluptate.
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="orange">
                <span>Lees verder...</span>
                <v-icon></v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card dark class="grey lighten-4">
            <v-card-title>
              <v-icon color="red" left>fas fa-exclamation
              </v-icon><span class="font-weight-bold black--text"><b>UPDATE:</b> VAKANTIEDAGEN</span>
            </v-card-title>
            <v-card-text class="black--text">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci alias architecto, atque corporis ea eos est eveniet id inventore, maxime nesciunt nisi non obcaecati odio quam quas quasi sunt suscipit tempore veniam. Aperiam in itaque laudantium molestiae quis! Accusantium dignissimos eligendi enim optio voluptas? Consequatur enim fuga non totam voluptate.
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="orange">
                <span>Lees verder...</span>
                <v-icon></v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data(){
    return{
      slider: [
        {
          src: 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1051&q=80',
          button: 'Paarden & Pony',
          text: 'Bekijk ons familie',
          route: '/horses',
          color: 'green'
        },
        {
          src: 'https://images.unsplash.com/photo-1594768816441-1dd241ffaa67?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80',
          button: 'Account',
          text: 'Klant of nieuw?',
          route: '/login',
          color: 'red'
        },
        {
          src: 'https://images.unsplash.com/photo-1485201567137-7771be2f710c?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=966&q=80',
          button: 'Geschiedenis',
          text: 'Meer weten over ons?',
          route: '/about',
          color: 'orange'
        }

      ]
    }
  }
}
</script>
<style scoped>
.my-span {
  background-color: blue;
  color: white;
  font-weight: bold;
  margin-right: 0;
  float: right;
}

</style>