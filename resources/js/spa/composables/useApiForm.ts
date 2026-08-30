import axios from 'axios';
import { reactive, ref } from 'vue';
import type { ValidationErrorEnvelope } from '@/api/types';

export function useApiForm<T extends Record<string, unknown>>(initial: T) {
  const values = reactive({ ...initial }) as T;
  const errors = ref<Record<string, string[]>>({});
  const error = ref<string | null>(null);
  const processing = ref(false);

  async function submit(action: () => Promise<void>): Promise<boolean> {
    processing.value = true;
    errors.value = {};
    error.value = null;
    try { await action(); return true; }
    catch (reason) {
      if (axios.isAxiosError<ValidationErrorEnvelope>(reason) && reason.response?.status === 422) {
        errors.value = reason.response.data.errors;
      } else if (axios.isAxiosError<{ message?: string }>(reason)) {
        error.value = reason.response?.data.message ?? 'The request could not be completed.';
      } else {
        error.value = reason instanceof Error ? reason.message : 'The request could not be completed.';
      }
      return false;
    } finally { processing.value = false; }
  }

  return { values, errors, error, processing, submit };
}
