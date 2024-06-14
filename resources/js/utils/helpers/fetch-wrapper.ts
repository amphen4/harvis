import { useStore } from 'vuex';

export const fetchWrapper = {
  get: request('GET'),
  post: request('POST'),
  put: request('PUT'),
  delete: request('DELETE')
};

function request(method: string) {
  return (url: string, body?: object) => {
    /* eslint-disable @typescript-eslint/no-explicit-any */
    const requestOptions: any = {
      method,
      headers: authHeader(url)
    };
    if (body) {
      requestOptions.headers['Content-Type'] = 'application/json';
      requestOptions.body = JSON.stringify(body);
    }
    return fetch(url, requestOptions).then(handleResponse);
  };
}

// helper functions

function authHeader(url: string) {
  // return auth header with jwt if user is logged in and request is to the api url
  //const store = useStore();
  const  user  = JSON.parse(localStorage.getItem('user'));
  const isLoggedIn = !!user?.state?.expiration_date && (new Date(user.state.expiration_date)) <= (new Date());
  const isApiUrl = url.startsWith('http://localhost:8000/api');
  if (isLoggedIn && isApiUrl) {
    return { Authorization: `Bearer ${user.state.token}` };
  } else {
    return {};
  }
}

function handleResponse(response: any) {
  return response.text().then((text: string) => {
    const data = text && JSON.parse(text);

    if (!response.ok) {
      const store = useStore();
      const { user, logout } = store.state.auth;
      if ([401, 403].includes(response.status) && user) {
        // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
        logout();
      }

      const error = (data && data.message) || response.statusText;
      return Promise.reject(error);
    }

    return data;
  });
}
