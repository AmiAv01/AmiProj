import axios from 'axios';
import { reactive, ref } from 'vue';
import type { ValidationErrorEnvelope } from '@/api/types';

export function useApiForm<T extends Record<string, unknown>>(initial: T) {
  const values = reactive({ ...initial }) as T;
  const errors = ref<Record<string, string[]>>({});
  const processing = ref(false);

  async function submit(action: () => Promise<void>): Promise<boolean> {
    processing.value = true;
    errors.value = {};
    try { await action(); return true; }
    catch (reason) {
      if (axios.isAxiosError<ValidationErrorEnvelope>(reason) && reason.response?.status === 422) {
        errors.value = reason.response.data.errors;
      } else { throw reason; }
      return false;
    } finally { processing.value = false; }
  }

  return { values, errors, processing, submit };
}
