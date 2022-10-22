import User from '@/interfaces/User';

export default class UserModel implements User {
  public id?: number | null;
  public name?: string;
  public email: string;

  constructor(props: User) {
    if (props.id) {
      this.id = props.id;
    }

    if (props.name) {
      this.name = props.name;
    }

    this.email = props.email;
  }
}
