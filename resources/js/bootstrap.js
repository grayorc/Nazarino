import htmx from 'htmx.org';
import axios from 'axios';

window.htmx = htmx;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
