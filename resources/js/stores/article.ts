import { defineStore } from 'pinia';
import ArticleFilters from '@/interfaces/article/ArticleFilters';
import axios from '@/plugins/axios';
import Comment from '@/interfaces/article/Comment';
import Article from '@/interfaces/article/Article';

export const useArticleStore = defineStore('article', () => {
  const load = async (filters?: ArticleFilters|{ user_id: number }) => {
    const params = { ...filters };

    return await axios.get('articles', { params });
  };

  const loadById = async (id: string) => {
    return await axios.get(`articles/${id}`);
  };

  const loadCategories = async () => {
    return await axios.get('articles/categories');
  };

  const saveComment = async (data: Comment) => {
    return await axios.post('comments', data);
  };

  const create = async (data: Article) => {
    return await axios.post('articles', data);
  };

  const update = async (id: string, data: Article) => {
    return await axios.put(`articles/${id}`, data);
  };

  const remove = async (id: string) => {
    return await axios.delete(`articles/${id}`);
  };

  return {
    load,
    loadCategories,
    loadById,
    saveComment,
    create,
    update,
    remove,
  };
});
