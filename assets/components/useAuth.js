// useAuth.js
import { useState, useEffect, createContext } from 'react';

export const AuthContext = createContext();

export function useAuth() {
  //récupère les infos de l'utilisateur dans le localStorage
  const storedUser = JSON.parse(localStorage.getItem('user')) || null;
  const [user, setUser] = useState(storedUser);

  useEffect(() => {
    console.log(user);
    // Save user to localStorage whenever it changes
    localStorage.setItem('user', JSON.stringify(user));
  }, [user]);

  return { user, setUser };
}
