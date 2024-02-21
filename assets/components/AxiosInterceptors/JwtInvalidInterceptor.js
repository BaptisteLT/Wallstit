import React,{ useEffect, useContext, useState } from 'react';
import axios from 'axios';
import { toast } from 'react-toastify';
import { AuthContext } from '../useAuth';



/* 
* Va envoyer une requête vers le serveur dans le cas où le call API intercepté retourne 401 pour demander un nouveau JWT token (en utilisant le refresh token en cookie) 
*
*/
function JwtInvalidInterceptor({ children }) {
    //Récupération du contexte afin d'accéder à user et setUser
    const { user, setUser } = useContext(AuthContext);
    const [isRetried, setIsRetried] = useState(false);
    const [isInterceptorReady, setIsInterceptorReady] = useState(false);


    async function refreshJwtAndRefreshTokens()
    {
        try {
            const response = await axios.get('/auth/refresh-jwt-token', {
                validateStatus: null //Permet que cette requête ne soit pas catch pas l'interceptor. Car si elle retournait 401, ça faisait une boucle infinie.
            });
            if (response.status === 200) {
                return {
                    data: response.data.data,
                    jwtToken: response.data.jwtToken
                };
            } else {
                // Handle unexpected status codes
                throw new Error('Error refreshing tokens (refresh token expired)');
            }
        //On entre ici quand le refresh token est expiré
        } catch (error) {
            setUser(null);
 
            throw error;
        }
    }
    
    async function setupInterceptor() {

        const responseInterceptor = axios.interceptors.response.use((response) => response, async (error) => {
            // On regarde si l'utilisateur n'est pas autorisé et que le serveur retourne 401
            if (error.response && error.response.status === 401) {
                try {
                    //Afin d'éviter une boucle infinie dans le cas où le refresh token est invalide ou qu'il y a une erreur côté serveur
                    if (!isRetried) {
                        setIsRetried(true);
                        // Attempt to refresh the jwtToken using the refresh token
                        const { data, jwtToken } = await refreshJwtAndRefreshTokens();
        
                        //Pour être sûr qu'on enregistre pas un undefined
                        if (data) {
                            setUser(data);
                        }
        
                        // Retry the original request with the new access token
                        const originalRequest = error.config;
                        originalRequest.headers.Authorization = `Bearer ${jwtToken}`;
                        return axios(originalRequest);
                    }
                    setIsRetried(false);
                } 
                catch (refreshError) 
                {
                    toast.error('Session has expired. Please log-in again.');
                    //We logout the user because the refresh token has become invalid or an error occured
                    setUser(null);
                    // La requête axios initiale va intercepter l'erreur
                    return Promise.reject(refreshError);
                }
            }

            // La requête axios initiale va intercepter l'erreur
            return Promise.reject(error);
        });

        // Set the interceptor ready status to true
        setIsInterceptorReady(true);

        // When the component is being removed from the DOM (unmounted), the cleanup function returned by setupInterceptor is executed.
        return () => {
            axios.interceptors.response.eject(responseInterceptor);
        };
    };

    useEffect(() => {
        setupInterceptor();
    }, [])

    /*This is a workaround I found, basically we need to wait for the interceptor to be ready.
    So when I refresh the page, I don't have the problem of the request beeing ahead.
    Because the goal for the interceptor is to catch any 401 (indicating an invalid JWT) and retrieve a new valid JWT Token. 
    Therefore, if the response interceptor is not ready, the page content is not loaded and the HTTP 401 remains unanswered.*/
    return isInterceptorReady ? children : null;
}

export default JwtInvalidInterceptor;