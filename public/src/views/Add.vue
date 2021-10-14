<template>
<!--TOEVOEGEN VAN PAARD/PONY-->
    <div>
        <v-container fill-height>
            <v-row justify="center" align="center" style="text-align: center">
                <v-col cols="12" sm="4">
                    <h1>Paard / Pony toevoegen</h1>
                    <v-alert v-if="success" v-show="success" elevation="7" type="success">Toegevoegd!
                    </v-alert>
<!--                    VALIDEERT MET VUETIFY EIGEN VALIDATIE-->
                    <v-form name='imageForm' enctype="multipart/form-data" ref="form" v-model="valid">
                        <!--Validatie max 12 letters, verplicht, regex alleen letters-->
                        <v-text-field
                            v-model="horse.name"
                            name="name"
                            :counter="12"
                            :rules="[
                       v => !!v || 'Naam is verplicht',
                       v => /^[a-zA-Z\s]*$/.test(v) || 'Alleen letters',
                       v => (v && v.length <= 12) || 'Naam is te lang',
                       ]"
                            label="Naam van paard"
                            required
                        ></v-text-field>
                        <v-alert v-if="name_error.length" v-show="true" elevation="7" class="hidden" type="error">Naam in gebruik
                        </v-alert>
                        <!--Leeftijd verplicht met regex alleen getallen toegestaan.max 2-->
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
                        <!--Veld voor paard ras is verplicht-->
                        <v-select
                            v-model="horse.race"
                            :items="items"
                            :rules="[v => !!v || 'Veld is verplicht']"
                            label="Soort"
                            required
                        ></v-select>
                        <!--Bio max 100 letters-->
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
                        <!--Valideert of afbeelding is toegestaan. Note: andere validatie erbij-->
                        <v-file-input
                            :rules="[
                       v => !v || v.size < 2000000 || 'Afbeelding is te groot. Max 2 MB',
                       v => (v && v.size > 0) || 'Afbeelding is nodig',
                       ]"
                            accept="image/png, image/jpeg"
                            v-model="horse.image"
                            @change="preview"
                            placeholder="Kies een afbeelding"
                            prepend-icon="fas fa-image"
                            label="Afbeelding"
                        ></v-file-input>
                        <v-img height="100" width="100" :src="preview_img"></v-img>
                        <v-alert v-if="img_error.length" v-show="true" elevation="7" class="hidden" type="error">{{
                                img_error
                            }}
                        </v-alert>
                    </v-form>
                    <v-btn text class="mr-4" @click="submit()">
                        Toevoegen
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    name: "Add",
    data() {
        return {
            //Paard velden
            horse: [
                {
                    name: ''
                },
                {
                    age: ''
                },
                {
                    race: ''
                },
                {
                    bio: ''
                },
                {
                    image: ''
                },
            ],
            preview_img: '',
            //Checkt of form data geldig is
            valid: false,
            image_valid: false,
            name_error: '',
            success: false,
            img_error: '',
            items: [
                'Paard',
                'Pony',
            ],
        }
    },
    methods: {
        preview() {
            //Pakt uit de temp storage de afbeelding
            this.preview_img = URL.createObjectURL(this.horse.image)
        },
        submit() {
            //Valideert de ingevulde velden
            this.$refs.form.validate()
            let img = this.horse.image['type'];
            //Valideert of het een afbeelding is [JPG/PNG]
            if ((img !== 'image/png') && (img !== 'image/jpeg')) {
                return this.img_error = 'Geen geldig afbeelding.';
            }
            this.image_valid = true
            //Returns true voor validatie velden & afbeeldingen
            if (this.valid && this.image_valid) {
                //Maak een aangepaste form data field
                let formData = new FormData()
                //Formdata velden
                formData.append("name", this.horse.name)
                formData.append("age", this.horse.age)
                formData.append("race", this.horse.race)
                formData.append("bio", this.horse.bio)
                formData.append("file", this.horse.image)

                this.$store.dispatch('addHorse', formData).then(() => {
                    //Reset velden
                    this.$refs.form.reset();
                    this.$refs.form.resetValidation()
                    this.name_error = ''
                    this.preview_img = ''
                    this.success = true
                }).catch(()=>{
                    //Error Note: door strenge regex validatie is dit de enige error die kan terugkomen.
                    return this.name_error = 'Naam in gebruik.';
                })
            }
        },
    }
}
</script>

<style scoped>

</style>
