import { defineStore } from 'pinia';
import { api, ensureCsrfCookie } from '@/api/client';
import type { ApiEnvelope, AuthUser } from '@/api/types';

interface LoginCredentials { email: string; password: string; remember?: boolean }
interface RegistrationData extends LoginCredentials { name: string; phoneNumber: string; password_confirmation: string }

export const useAuthStore = defineStore('auth', {
  state: () => ({ user: null as AuthUser | null, loaded: false }),
  getters: { authenticated: state => state.user !== null, admin: state => state.user?.isAdmin === true },
  actions: {
    async load(): Promise<void> {
      if (this.loaded) return;
      try {
        const response = await api.get<ApiEnvelope<AuthUser>>('/auth/user');
        this.user = response.data.data;
      } catch { this.user = null; }
      finally { this.loaded = true; }
    },
    async login(credentials: LoginCredentials): Promise<void> {
      await ensureCsrfCookie();
      const response = await api.post<ApiEnvelope<AuthUser>>('/auth/login', credentials);
      this.user = response.data.data;
      this.loaded = true;
    },
    async adminLogin(credentials: LoginCredentials): Promise<void> {
      await ensureCsrfCookie();
      const response = await api.post<ApiEnvelope<AuthUser>>('/auth/admin-login', credentials);
      this.user = response.data.data;
      this.loaded = true;
    },
    async register(data: RegistrationData): Promise<void> {
      await ensureCsrfCookie();
      await api.post('/auth/register', data);
    },
    async logout(): Promise<void> {
      await api.post('/auth/logout');
      this.user = null;
      this.loaded = true;
    },
  },
});
