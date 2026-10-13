import axios from 'axios';
// import tinymce from 'tinymce';
window.axios = axios;
// window.tinymce = tinymce;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';