import React, { useContext, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import axios from 'axios';
import { AuthContext } from './useAuth';


function GoogleCallback() {
    const { setUser } = useContext(AuthContext);

    const navigate = useNavigate();
    const location = useLocation();

    // Function to parse query parameters from the URL
    function getQueryParams()
    {
        const searchParams = new URLSearchParams(location.search);
        return Object.fromEntries(searchParams.entries());
    };

    useEffect(() => {
        // Get query parameters
        const queryParams = getQueryParams();
    
        // Use code and state as needed
        const { code, state } = queryParams;
      
        axios.post('/auth/getTokens', {
            code: code,
            state: state
        })
        .then(function (response) {
            // handle success
            //On récupère le jwt
            const jwtAndRefreshTokenExpiration = response.data;
            setUser(jwtAndRefreshTokenExpiration);
        })
        .catch(function (error) {
            // handle error
            alert('An error occured while authenticating with Google');
        })

        // Redirect to the main page (maybe todo last page?)
        navigate('/');
    }, []);

    return <div>Handling Google callback...</div>;
};

export default GoogleCallback;
