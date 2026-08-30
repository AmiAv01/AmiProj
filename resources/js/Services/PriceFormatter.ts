import { DISPLAY_CURRENCY_CODE } from '@/Config/AppConfig';

export function formatPrice(value: number | string | null | undefined): string {
    const normalizedValue = typeof value === 'string'
        ? value.replace(',', '.')
        : value;
    const price = Number(normalizedValue);

    if (!Number.isFinite(price)) {
        return '0,00';
    }

    return new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(price);
}

export function formatMoney(value: number | string | null | undefined): string {
    return `${formatPrice(value)} ${DISPLAY_CURRENCY_CODE}`;
}
