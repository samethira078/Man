import axios from 'axios'
import router from "../router";

axios.defaults.baseURL = 'http://samet.nl/'
axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("token");
export default {
    namespace: true,
    state: {
        paid_horses: [],
        users: [],
        all_orders: [],
    },
    getters: {},
    mutations: {
        userHorses(state, response) {
            state.paid_horses = response;
        },
        userList(state, data){
            state.users = data;
        }
    },
    actions: {
        submitPayment(context, info) {
            return new Promise((resolve, reject) => {
                axios.post('api/submit_order', {
                    naam: info.naam,
                    adres: info.adres,
                    horses: info.horse,
                    nummer: info.nummer,
                }).then(response => {
                    resolve(response.data)
                }).catch(() => {
                    reject(reject)
                })
            })
        },
        paidHorses(context) {
            axios.get('/api/user_horses').then(response => {
                context.commit('userHorses', response.data)
            }).catch(() => {
                router.push('/logout')
            })
        },
        getDates(context, date) {
            return new Promise((resolve, reject) => {
                axios.post('api/tijden', {
                    date: date.date,
                    horse: date.paard_id,
                }).then(response => {
                    resolve(response.data)
                }).catch(() => {
                    reject(reject)
                })
            })
        },
        addHorse(context, data){
            return new Promise((resolve, reject) => {
                // axios.defaults.headers.common["content-type"] = "multipart/form-data";
                axios.post('api/add_horse', data).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        allUsers(context, data){
            return new Promise((resolve, reject) => {
                // axios.defaults.headers.common["content-type"] = "multipart/form-data";
                axios.post('api/add_horse', data).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        checkOrder(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/order', {
                    date: data.date,
                    tijd: data.tijd,
                    paard_id: data.paard_id,
                    id: data.id
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    if (error.response.status === 422) {
                        reject(error);
                    }
                })
            })
        },
        allOrders(){
            return new Promise((resolve, reject) => {
                axios.get('api/all_orders').then(response => {
                    resolve(response)
                }).catch(() => {
                    reject(reject)
                    router.push('/logout')
                })
            })
        },
        removeOrder(context, id) {
            return new Promise((resolve, reject) => {
                axios.post('api/remove_order', {
                    id: id
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        removeHorse(context, id) {
            return new Promise((resolve, reject) => {
                axios.post('api/remove_horse', {
                    id: id
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        removeUser(context, id) {
            return new Promise((resolve, reject) => {
                axios.post('api/remove_user', {
                    id: id
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        userList(){
            return new Promise((resolve, reject) => {
                axios.get('api/user_list').then(response => {
                    resolve(response)
                }).catch(() => {
                    reject(reject)
                    router.push('/logout')
                })
            })
        },
        updateSelectedHorse(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/update_selected_horse', {
                    id: data.id,
                    name: data.name,
                    age: data.age,
                    bio: data.bio,
                    type: data.type,
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        updateSelectedOrder(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/update_selected_order', {
                    id: data.id,
                    fullname: data.fullname,
                    adres: data.adres,
                    datum: data.datum,
                    tijd: data.tijd,
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        },
        updateSelectedUser(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/update_selected_user', {
                    id: data.id,
                    rank: data.rank,
                    mail: data.email
                }).then(() => {
                    resolve(resolve)
                }).catch(error => {
                    reject(error);
                })
            })
        }
    }
}
