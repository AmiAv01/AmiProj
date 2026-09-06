function readMetaContent(name: string): string {
    const value = document.querySelector<HTMLMetaElement>(`meta[name="${name}"]`)?.content;

    if (!value) {
        throw new Error(`Missing application configuration meta tag: ${name}`);
    }

    return value;
}

function readPositiveInteger(name: string): number {
    const value = Number.parseInt(readMetaContent(name), 10);

    if (!Number.isInteger(value) || value < 1) {
        throw new Error(`Invalid positive integer application configuration: ${name}`);
    }

    return value;
}

export const DISPLAY_CURRENCY_CODE = readMetaContent('display-currency-code');
export const CART_QUANTITY_MIN = readPositiveInteger('cart-quantity-min');
export const CART_QUANTITY_MAX = readPositiveInteger('cart-quantity-max');
