import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.withCredentials = true;

window.axios.interceptors.response.use(
    response => response,
    error => {
        const status = error.response ? error.response.status : null;

        if (status >= 500) {
            window.dispatchEvent(new CustomEvent('http-error', {
                detail: {
                    severity: 'error',
                    summary: 'Server Error',
                    detail: '系統發生錯誤，請稍後再試或聯繫管理員。'
                }
            }));
        }
        return Promise.reject(error);
    }
);
