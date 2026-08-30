import {defineStore} from "pinia";
import axios from "axios";
import { CART_QUANTITY_MAX, CART_QUANTITY_MIN } from "@/Config/AppConfig";


const CART_COUNT_STORAGE_KEY = 'cart_item_count';
export interface CartItem {
    dt_id: number;
    quantity: number;
    [key: string]: unknown;
}

interface CartPayload {
    data: { items: CartItem[]; cartCount: number };
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        cartData: [] as CartItem[],
        cartCount: parseInt(localStorage.getItem(CART_COUNT_STORAGE_KEY) || '0', 10),
    }),
    actions: {
        deleteDetailFromCart(id: number){
            axios
                .delete<CartPayload>(`/api/v1/cart/${id}`)
                .then((res) => {
                    this.cartData = res.data.data.items;
                    this.setCartCount(res.data.data.cartCount);
                })
                .catch((err) => console.log(err));
        },
        async changeDetailQuantity(id: number, quantity: number){
            if (!Number.isFinite(quantity)) {
                return;
            }

            const normalizedQuantity = Math.min(
                CART_QUANTITY_MAX,
                Math.max(CART_QUANTITY_MIN, Math.trunc(quantity)),
            );

            try {
                const res = await axios.put<CartPayload>(`/api/v1/cart/${id}`, { quantity: normalizedQuantity });
                this.cartData = res.data.data.items;
                this.setCartCount(res.data.data.cartCount);
            } catch (err) {
                console.error('Не удалось изменить количество товара:', err);
                throw err;
            }
        },
        incCartCount(){
            ++this.cartCount;
        },
        setDetails(items: CartItem[]){
            this.cartData = items;
        },
        setCartCount(newCount: number){
            this.cartCount = newCount;
            localStorage.setItem(CART_COUNT_STORAGE_KEY, String(newCount));
        }
    }
})
