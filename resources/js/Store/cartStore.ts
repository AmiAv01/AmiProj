import {defineStore} from "pinia";
import axios from "axios";


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
        deleteDetailFromCart(id: number, showConfirmation = true){
            const performDelete = () => {
                axios
                    .delete<CartPayload>(`/api/v1/cart/${id}`)
                    .then((res) => {
                        this.cartData = res.data.data.items;
                        this.setCartCount(res.data.data.cartCount);
                    })
                    .catch((err) => console.log(err));
            };

            if (showConfirmation) {
                if (confirm('Вы желаете удалить этот товар из корзины? ДА/НЕТ')) {
                    performDelete();
                }
            } else {
                performDelete();
            }
        },
        async changeDetailQuantity(id: number, quantity: number){
            // If quantity is 0 or less, show confirmation before deleting
            if (quantity <= 0) {
                if (confirm('Вы желаете удалить этот товар из корзины? ДА/НЕТ')) {
                    this.deleteDetailFromCart(id, false);
                }
                return;
            }

            try {
                const res = await axios.put<CartPayload>(`/api/v1/cart/${id}`, { quantity });
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
