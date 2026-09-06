import axios from 'axios';

export async function ensureCsrfCookie(): Promise<void> {
  await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
}

export const api = axios.create({
  baseURL: '/api/v1',
  headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
  withCredentials: true,
  withXSRFToken: true,
});
