import Comment from '@/interfaces/article/Comment';

export default interface Article {
  id?: number,
  content: string,
  title: string
  comments?: Comment[],
  author_name?: string,
  comments_count?: number
  category?: string,
  can?: {
    update: boolean,
    delete: boolean,
  },
  date?: string,
}
