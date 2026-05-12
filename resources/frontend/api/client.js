import axios from 'axios';

const csrfMeta = document.querySelector('meta[name="csrf-token"]');

const client = axios.create({
    baseURL: '/',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        ...(csrfMeta ? { 'X-CSRF-TOKEN': csrfMeta.content } : {}),
    },
});

export default client;
