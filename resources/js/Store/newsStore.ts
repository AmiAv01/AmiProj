import {defineStore} from "pinia";
import axios from "axios";

export interface NewsPost {
    id?: number;
    title: string;
    description: string;
    [key: string]: unknown;
}

interface NewsPage {
    data: NewsPost[];
    links: unknown[];
    [key: string]: unknown;
}

interface NewsPayload { data: { items: NewsPage } }

export const useNewsStore = defineStore('news', {
    state: () => ({ newsData: { data: [], links: [] } as NewsPage }),
    actions: {
        setNews(items: NewsPage){
            this.newsData = items;

        },
        async addPost(title: string, description: string){
            const res = await axios
                .post<NewsPayload>(`/api/v1/admin/news`, {
                    title,
                    description,
                });

            this.setNews(res.data.data.items);
        },
        async deletePost(id: number){
            const res = await axios.delete<NewsPayload>(`/api/v1/admin/news/${id}`);
            this.setNews(res.data.data.items);
        },
        async editPost(id: number, title: string, description: string) {
            const res = await axios.patch<NewsPayload>(`/api/v1/admin/news/${id}`, {
                    title,
                    description,
                });
            this.setNews(res.data.data.items);
        }
    }
})
