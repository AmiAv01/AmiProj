import {defineStore} from "pinia";
import axios from "axios";

export interface NewsPost {
    id?: number;
    title: string;
    description: string;
    [key: string]: unknown;
}

interface NewsPayload { data: { items: NewsPost[] } }

export const useNewsStore = defineStore('news', {
    state: () => ({ newsData: [] as NewsPost[] }),
    actions: {
        setNews(items: NewsPost[]){
            this.newsData = items;

        },
        addPost(title: string, description: string){
            axios
                .post<NewsPayload>(`/api/v1/admin/news`, {
                    title,
                    description,
                })
                .then((res) => {
                    console.log(res.data)
                    this.setNews(res.data.data.items)
                })
                .catch((err) => console.log(err));
        },
        deletePost(id: number){
            axios.delete<NewsPayload>(`/api/v1/admin/news/${id}`)
                .then(res => {
                    console.log(res.data)
                    this.setNews(res.data.data.items)
                })
                .catch(err => console.log(err))
        },
        editPost(id: number, title: string, description: string) {
            console.log(id);
            axios
                .patch<NewsPayload>(`/api/v1/admin/news/${id}`, {
                    title,
                    description,
                })
                .then(res => {
                    console.log(res.data)
                    this.setNews(res.data.data.items)
                })
                .catch((err) => console.log(err));
        }
    }
})
