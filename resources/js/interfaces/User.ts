export default interface User {
  id?: number | null,
  email: string,
  name?: string,
  password?: string
  password_confirmation?: string
}
