import { AuthContext } from '@/hooks/use-auth';
import React, { useState, useEffect, ReactNode } from 'react';

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [authToken, setAuthToken] = useState<string | null>(
    localStorage.getItem('jwt_token') || null
  );

  const login = (token: string) => {
    setAuthToken(token);
    localStorage.setItem('jwt_token', token);
  };

  const logout = () => {
    setAuthToken(null);
    localStorage.removeItem('jwt_token');
  };

  useEffect(() => {
    // validate
  }, [authToken]);

  return (
    <AuthContext.Provider value={{ authToken, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
