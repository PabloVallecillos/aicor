import { AuthContext } from '@/hooks/use-auth';
import React, { useState, ReactNode } from 'react';
import { JWT_LOCAL_STORAGE_KEY } from '@/constants/local-storage.tsx';

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [authToken, setAuthToken] = useState<string | null>(
    localStorage.getItem(JWT_LOCAL_STORAGE_KEY) || null
  );

  const login = (token: string) => {
    setAuthToken(token);
    localStorage.setItem(JWT_LOCAL_STORAGE_KEY, token);
  };

  const logout = () => {
    setAuthToken(null);
    localStorage.removeItem(JWT_LOCAL_STORAGE_KEY);
  };

  return (
    <AuthContext.Provider value={{ authToken, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
