import {defineStore} from "pinia";
import axios from "axios";


const CART_COUNT_STORAGE_KEY = 'cart_item_count';
export const useCartStore = defineStore('cart', {
    state: () => ({
        cartData: [],
        cartCount: parseInt(localStorage.getItem(CART_COUNT_STORAGE_KEY) || '0', 10),
    }),
    actions: {
        deleteDetailFromCart(id, showConfirmation = true){
            const performDelete = () => {
                axios
                    .delete(`/cart/${id}`)
                    .then((res) => {
                        this.cartData = res.data.items;
                        this.setCartCount(res.data.newCartCount);
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
        async changeDetailQuantity(id, quantity){
            // If quantity is 0 or less, show confirmation before deleting
            if (quantity <= 0) {
                if (confirm('Вы желаете удалить этот товар из корзины? ДА/НЕТ')) {
                    this.deleteDetailFromCart(id, false);
                }
                return;
            }

            try {
                const res = await axios.put(`/cart/${id}`, { quantity });
                this.cartData = res.data.items;
                this.setCartCount(res.data.newCartCount);
            } catch (err) {
                console.error('Не удалось изменить количество товара:', err);
                throw err;
            }
        },
        incCartCount(){
            ++this.cartCount;
        },
        setDetails(items){
            this.cartData = items;
        },
        setCartCount(newCount){
            this.cartCount = newCount;
            localStorage.setItem(CART_COUNT_STORAGE_KEY, newCount);
        }
    }
})
