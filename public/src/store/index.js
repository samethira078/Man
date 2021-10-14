import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import Auth from './auth';
import Horses from './horses'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://samet.nl:80'
export const store = new Vuex.Store({
    state: {
        horses: [],

    },
    getters: {},
    mutations:
        {
            slotHorses(state, horses) {
                state.horses = horses;
            },
        },
    actions: {
        grabOrders() {
            return new Promise((resolve) => {
                axios.get('api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    resolve(response)
                })
            })
        },
        async grabHorses(context) {
            await axios.get('/api/horses', {}).then(response => {
                const horses = response.data;
                context.commit('slotHorses', horses);
            })
        },
    },
    modules: {
        Auth, Horses
    }
})
