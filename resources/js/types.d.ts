declare global {
  interface Window {
    appData: {
      markers?: any[];
      user?: any;
      isAdmin?: boolean;
      csrfToken?: string;
      usersCount?: number;
      markersCount?: number;
      loginRoute?: string;
      registerRoute?: string;
      error?: string;
      oldEmail?: string;
    };
  }
}

export {};
