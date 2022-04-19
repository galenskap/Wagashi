import { createToast } from "mosha-vue-toastify";
import 'mosha-vue-toastify/dist/style.css';


window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Get the same look and behaviour for every toast
 */
window.toast = (message, type = "danger") => {
    createToast(
        message,
        {
            type: type,
            transition: 'zoom',
        }
    );
}

window.token = (localStorage.getItem('token')) ? `Bearer ${localStorage.getItem('token')}` : false;

