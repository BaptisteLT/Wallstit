import React,{ useEffect, useContext } from 'react';
import axios from 'axios';

import { AuthContext } from './useAuth';



/* 
* Va envoyer une requête vers le serveur dans le cas où le call API intercepté retourne 401 pour demander un nouveau JWT token (en utilisant le refresh token en cookie) 
*
*/
function JwtInvalidInterceptor({ children }) {
    //Récupération du contexte afin d'accéder à user et setUser
    const { user, setUser } = useContext(AuthContext);
    let isRetried = false;

    async function refreshJwtAndRefreshTokens()
    {
        try {
            const response = await axios.get('/auth/refresh-jwt-token');
            console.log(response.status);
            if (response.status === 200) {
                return {
                    data: response.data.data,
                    jwtToken: response.data.jwtToken
                };
            } else {
                // Handle unexpected status codes
                throw new Error('Error refreshing tokens (refresh token expired)');
            }
        } catch (error) {
            setUser(null);
            console.error(error)
            throw error;
        }
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
                        const { data, jwtToken } = await refreshJwtAndRefreshTokens();
    
                        //Pour être sûr qu'on enregistre pas un undefined
                        if(data)
                        {
                            setUser(data);    
                        }

                        // Retry the original request with the new access token
                        const originalRequest = error.config;
                        originalRequest.headers.Authorization = `Bearer ${jwtToken}`;
                        return axios(originalRequest);
                    }
                } 
                catch (refreshError) {
                    //We logout the user because the refresh token has become invalid or an error occured
                    setUser(null);
                    // Handle refresh error (e.g., logout the user)
                    console.log('Error fetching data after token refresh:', refreshError);
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