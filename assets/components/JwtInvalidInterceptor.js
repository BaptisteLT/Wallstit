import React,{ useEffect, useContext } from 'react';
import axios from 'axios';

import { AuthContext } from './useAuth';




function JwtInvalidInterceptor({ children }) {
    //Récupération du contexte afin d'accéder à user et setUser
    const { user, setUser } = useContext(AuthContext);
    let isRetried = false;

    async function refreshJwtAndRefreshTokens()
    {
        //todo: coté back retourner un nouveau token jwt en cookie et un nouveau refresh token en cookie
        return {jwtToken: 'theJwtToken'};//todo: setUser
    }
    

    useEffect(() => {
        const responseInterceptor = axios.interceptors.response.use(response => response, async (error) => {
            // On regarde si l'utilisateur n'est pas autorisé et que le serveur retourne 401
            if (error.response && error.response.status === 401) {
                try {
                    //Afin d'éviter une boucle infinie dans le cas où le refresh token est invalide ou qu'il y a une erreur côté serveur
                    if(!isRetried)
                    {
                        isRetried = true;
                        // Attempt to refresh the jwtToken using the refresh token
                        const { jwtToken } = await refreshJwtAndRefreshTokens();
    
                        setUser(jwtToken);
                        console.log(user);
    
                        console.log(jwtToken);
                        // Retry the original request with the new access token
                        const originalRequest = error.config;
                        //originalRequest.headers.Authorization = `Bearer ${newAccessToken}`;
                        return axios(originalRequest);
                    }
                } 
                catch (refreshError) {
                    //TODO: logout the user because the refresh token has become invalid
                    setUser(null);
                    // Handle refresh error (e.g., logout the user)
                    console.error('Error fetching data after token refresh:', refreshError);
                    // La requête axios initiale va intercepter l'erreur
                    return Promise.reject(refreshError);
                }
            }
      
            // La requête axios initiale va intercepter l'erreur
            return Promise.reject(error);
        }
      );

        return () => {
            axios.interceptors.response.eject(responseInterceptor);
        }
    }, [])

    return children
}

export default JwtInvalidInterceptor;