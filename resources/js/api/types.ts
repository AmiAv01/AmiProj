export interface ApiEnvelope<T> {
  data: T;
  message?: string;
}

export interface ValidationErrorEnvelope {
  message: string;
  errors: Record<string, string[]>;
}

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  approved: boolean;
  isAdmin: boolean;
}

export const ORDER_STATUSES = ['Новый', 'Принят', 'Выполнен'] as const;
export type OrderStatus = (typeof ORDER_STATUSES)[number];

export interface Paginated<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: unknown[];
}

export type PageData = Record<string, unknown>;
