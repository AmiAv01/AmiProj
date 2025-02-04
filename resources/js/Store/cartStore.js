import {defineStore} from "pinia";
import axios from "axios";

export const useCartStore = defineStore('cart', {
    state: () => ({ cartData: [] }),
    actions: {
        deleteDetailFromCart(id){
            axios
                .delete(`/cart/${id}`)
                .then((res) => {
                    console.log(res.data.items)
                    this.cartData = res.data.items;
                })
                .catch((err) => console.log(err));
        },
        changeDetailQuantity(id, quantity){
            axios
                .put(`/cart/${id}`, { quantity })
                .then((res) => {
                    console.log(res.data.items)
                    this.cartData = res.data.items;
                })
                .catch((err) => console.log(err));
        },
        setDetails(items){
            this.cartData = items;
        },
    }
})
