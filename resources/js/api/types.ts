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
  approved: boolean;
  isAdmin: boolean;
}

export interface Paginated<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: unknown[];
}

export type PageData = Record<string, unknown>;
