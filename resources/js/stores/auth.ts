import { router } from '@/router';
import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';

const baseUrl = '';
//const baseUrl = 'http://localhoat:8000/api';

const auth = {
  namespaced: true,
  state: {
    // initialize state from local storage to enable user to stay logged in
    /* eslint-disable-next-line @typescript-eslint/ban-ts-comment */
    // @ts-ignore
    user: JSON.parse(localStorage.getItem('user')),
    returnUrl: null
  },
  mutations: {
    setUser(state, payload){
      state.user = payload;
    },
    setReturnUrl(state, payload){
      state.returnUrl = payload;
    }
  },
  actions: {
    async login({ commit, state }, payload) {
      const user = await fetchWrapper.post(`${baseUrl}/api/login`, { email: payload.username, password: payload.password });
      commit('setUser', user);
      // store user details and jwt in local storage to keep user logged in between page refreshes
      localStorage.setItem('user', JSON.stringify(user));
      // redirect to previous url or default to home page
      router.push(state.returnUrl || '/dashboard');
    },
    logout({ commit }) {
      commit('setUser', null);
      localStorage.removeItem('user');
      //router.push('/auth/login');
    }
  }
};

export default auth;
