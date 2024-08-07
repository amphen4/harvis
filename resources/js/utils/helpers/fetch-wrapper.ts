import store from '@/stores';

//console.log('import store ql', store);
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
      if( method == 'GET' ){
        url = updateURLWithParams(url, body);
      }else{
        requestOptions.headers['Content-Type'] = 'application/json';
        requestOptions.body = JSON.stringify(body);
      }
      
    }
    store.dispatch('harvis/ADD_LOADING_COUNTER', 1);
    return fetch(url, requestOptions).then(handleResponse);
  };
}

function updateURLWithParams(url : string, params : object) {
  // Crear un objeto URL a partir de la cadena proporcionada
  let urlObj = new URL(url);

  // Obtener los parámetros de consulta existentes
  let queryParams = new URLSearchParams(urlObj.search);

  // Agregar o actualizar los parámetros de consulta desde el objeto
  for (let key in params) {
      queryParams.set(key, params[key]);
  }

  // Actualizar la parte de la búsqueda de la URL con los nuevos parámetros
  urlObj.search = queryParams.toString();

  return urlObj.toString();
}
// helper functions

function authHeader(url: string) {
  // return auth header with jwt if user is logged in and request is to the api url
  //const store = useStore();
  
  const  user  = JSON.parse(localStorage.getItem('user'));
  const isLoggedIn = !!user?.expiration_date && (new Date(user.expiration_date)) > (new Date());
  const isApiUrl = url.startsWith(`${import.meta.env.VITE_API_URL}`);
  if (isLoggedIn && isApiUrl) {
    console.log('le agregare el bearer token');
    return { Authorization: `Bearer ${user.token}`, Accept: 'application/json' };
  } else {
    return {};
  }
}

function handleResponse(response: any) {
  return response.text().then((text: string) => {
    const data = text && JSON.parse(text);
    store.dispatch('harvis/ADD_LOADING_COUNTER', -1);
    if (!response.ok) {
      const { user } = store.state.auth;
      if ([401, 403].includes(response.status) && user) {
        // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
        console.log('401 o 403 -> logout()');
        store.dispatch('auth/logout');
      }
      if ([422].includes(response.status) && user) {
        // validation error messages from laravel
        const messages : Array<String> = [];
        if( data.errors){
          Object.keys(data.errors).forEach( e => messages.push(data.errors[e][0]));
        }else{
          if( data.message && (typeof data.message == 'string') ){
            messages.push(data.message);
          }
        }
        if( messages.length ){
          store.dispatch('harvis/RAISE_SNACKBAR', messages);
        }
      }
      const error = (data && data.message) || response.statusText;
      return Promise.reject(error);
    }
    return data;
  });
}
