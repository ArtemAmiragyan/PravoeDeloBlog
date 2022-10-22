export const notAuthenticated = ({ store }: { store: any }) => {
  if (!store.auth.isAuthenticated) {
    return;
  }

  return { name: 'home' };
};

export const authenticated = ({ store }: { store: any }) => {
  if (store.auth.isAuthenticated) {
    return;
  }

  return { name: 'login' };
};
