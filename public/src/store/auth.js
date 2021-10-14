import axios from 'axios'
// import router from '../router'
axios.defaults.baseURL = 'http://samet.nl/'
export default {
    namespace: true,
    state: {
        token: localStorage.getItem('token'),
    },
    getters: {
        loggedIn(state) {
            return state.token != null
        },
    },
    mutations: {
        saveToken(state, token) {
            localStorage.setItem('token', token);
        },
        removeToken(state) {
            if (state.token !== null) {
                localStorage.removeItem('token')
                state.token = null;
            }
        }
    },
    actions: {
        async destroyToken(context) {
            await axios.delete('api/logout', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                window.location.href = "/login"
                context.commit('removeToken', response);
            }).catch(() => {
                window.location.href = "/login"
                context.commit('removeToken');
            })
        },
        LoginForm(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/login', {
                    username: data.username,
                    password: data.password
                })
                    .then(response => {
                        context.commit('saveToken', response.data);
                        resolve(response);
                    }).catch(() => {
                    reject(reject);
                })

            })
        },
        RegisterForm(context, data) {
            return new Promise((resolve, reject) => {
                axios.post('api/register', {
                    username: data.username,
                    password: data.password
                })
                    .then(response => {
                        resolve(response);
                    }).catch(response => {
                    reject(response);
                })

            })
        },
        getUserInfo() {
            return new Promise((resolve, reject) => {
                axios.get('api/settings', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    resolve(response)
                }).catch(() => {
                    reject(reject)
                })
            })
        }
    }
}
