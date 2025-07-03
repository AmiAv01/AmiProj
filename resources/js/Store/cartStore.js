import {defineStore} from "pinia";
import axios from "axios";


const CART_COUNT_STORAGE_KEY = 'cart_item_count';
export const useCartStore = defineStore('cart', {
    state: () => ({
        cartData: [],
        cartCount: parseInt(localStorage.getItem(CART_COUNT_STORAGE_KEY) || '0', 10),
    }),
    actions: {
        deleteDetailFromCart(id){
            axios
                .delete(/cart/${id})
                .then((res) => {
                    this.cartData = res.data.items;
                    this.setCartCount(res.data.newCartCount);
                })
                .catch((err) => console.log(err));
        },
        changeDetailQuantity(id, quantity){
            axios
                .put(/cart/${id}, { quantity })
                .then((res) => {
                    this.cartData = res.data.items;
                    this.setCartCount(res.data.newCartCount);
                })
                .catch((err) => console.log(err));
        },
        setDetails(items){
            this.cartData = items;
        },
        setCartCount(newCount){
            console.log('Count');
            console.log(newCount);
            this.cartCount = newCount;
            localStorage.setItem(CART_COUNT_STORAGE_KEY, newCount);
        }
    }
})