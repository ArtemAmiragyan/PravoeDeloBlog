import axios from 'axios';
import { TOKEN } from '@/constants/cookie-keys';
import { getCookie } from 'typescript-cookie';

const instance = axios.create({
  baseURL: '/api/',
  withCredentials: true,
  headers: {
    common: {
      'X-Requested-With': 'XMLHttpRequest',
    },
    Authorization: `Bearer ${getCookie(TOKEN) || ''}`,
  },
});

// TODO: Add global errors cather and show them in notification

export default instance;
