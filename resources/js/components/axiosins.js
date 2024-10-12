import axios from 'axios';

const axiosInst = axios.create();

axiosInst.interceptors.request.use((config) => {
  const userString = localStorage.getItem('user');
  if (userString) {
    try {
      const userls = JSON.parse(userString); // Parsear la cadena JSON
      const token = userls.accessToken; // Acceder al token
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    } catch (error) {
      console.error('Error al parsear el JSON de localStorage:', error);
    }
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

axiosInst.interceptors.response.use(
  response => response,
  error => {
    if (error.response && (error.response.status === 401 || error.response.status === 403)) {
      console.error('Authorization error', error.response.status);   
      localStorage.removeItem('user');     
      window.location.href = '/auth/login';
    }
    return Promise.reject(error);
  }
);

export default axiosInst;
