export default interface ArticleFilters {
  query: string,
  categories: [],
  date_from: string
  date_to: string,
  user_id?: number,
  page?: number,
}
