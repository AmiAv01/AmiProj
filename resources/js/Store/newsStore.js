import {defineStore} from "pinia";
import axios from "axios";

export const useNewsStore = defineStore('news', {
    state: () => ({ newsData: [] }),
    actions: {
        setNews(items){
            this.newsData = items;

        },
        addPost(title, description){
            axios
                .post(`/admin/resource/news/store`, {
                    title,
                    description,
                })
                .then((res) => {
                    console.log(res.data)
                    this.setNews(res.data.items)
                })
                .catch((err) => console.log(err));
        },
        deletePost(id){
            axios.delete(`/admin/resource/news/${id}`)
                .then(res => {
                    console.log(res.data)
                    this.setNews(res.data.items)
                })
                .catch(err => console.log(err))
        },
        editPost(id, title, description) {
            console.log(id);
            axios
                .patch(`/admin/resource/news/${id}`, {
                    title,
                    description,
                })
                .then(res => {
                    console.log(res.data)
                    this.setNews(res.data.items)
                })
                .catch((err) => console.log(err));
        }
    }
})
