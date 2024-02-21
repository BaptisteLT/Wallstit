import React,{ useEffect, useState } from 'react';
import axios from 'axios';

/* 
* Va envoyer une requête vers le serveur dans le cas où le call API intercepté retourne 401 pour demander un nouveau JWT token (en utilisant le refresh token en cookie) 
*
*/
function CSRFTokenInterceptor({ CSRFToken, setCSRFToken, children }) {

    const [ CSRFInterceptorReady, setCSRFInterceptorReady ] = useState(false);
    
    useEffect(() => {
        //Cette partie va récupérer le CSRF Token et le passer dans le header
        const setCSRFHeaderInterceptor = axios.interceptors.request.use(function (config) {
            //On va attacher le token CSRF automatiquement à toutes les requêtes
            if(CSRFToken != null)
            {
                config.headers['X-CSRF-TOKEN'] = CSRFToken;
            }

            return config;
            
        }, function (error) {
            // Do something with request error
            return Promise.reject(error);
        });

        return () => {
            axios.interceptors.request.eject(setCSRFHeaderInterceptor);
        };
    }, [CSRFToken])
    
    useEffect(() => {
        
        //Cette partie va récupérer le token csrf s'il existe et le mettre dans un useState
        const getCSRFInterceptor = axios.interceptors.response.use(function (response) {
            if(response.data.hasOwnProperty('csrfToken'))
            {
                setCSRFToken(response.data.csrfToken);
            }
            return response;
        });

        // Set the interceptor ready status to true
        setCSRFInterceptorReady(true);
        
        return () => {
            axios.interceptors.response.eject(getCSRFInterceptor);
        };
    }, [])

    /*This is a workaround I found, basically we need to wait for the interceptor to be ready.
    So when I refresh the page, I don't have the problem of the request beeing ahead.
    Because the goal for the interceptor is to catch any 401 (indicating an invalid JWT) and retrieve a new valid JWT Token. 
    Therefore, if the response interceptor is not ready, the page content is not loaded and the HTTP 401 remains unanswered.*/
    return CSRFInterceptorReady ? children : null;
}

export default CSRFTokenInterceptor;