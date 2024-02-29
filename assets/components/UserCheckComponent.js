import React, { useEffect, useContext } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import { AuthContext } from './useAuth';
import { toast } from 'react-toastify';

/**
 * Va vérifier que le refresh token en localStorage n'est pas expiré, afin de déconnecter l'utilisateur dans le cas présent
 */
function UserCheckComponent({ children }) {

  let { user, setUser } = useContext(AuthContext);
  const location = useLocation();

  useEffect(() => {
    checkRefreshTokenExpiration();
  }, [location]);

  function checkRefreshTokenExpiration() {
    if(user)
    {
      console.log('user is:');
      

      console.log('checking refresh token');
      const currentTimeStamp = new Date().getTime() / 1000;
      
      //Si le refresh token a expiré on fait en sorte que l'utilisateur doit se reconnecter
      if(user.refreshTokenExpiresAt < currentTimeStamp)
      {
        console.log('refresh token expired.');
        toast.error('Session has expired. Please log-in again.')
        setUser(null);
      }
    }
  }

  return <>{children}</>;
}

export default UserCheckComponent;