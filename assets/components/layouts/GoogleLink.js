import React from 'react';
import axios from "axios";


const GoogleLink = () => {

    function getGoogleOAuthHref(e)
    {
        e.preventDefault();
        //Request to the controller
        axios.get('/api/get-google-oauth2-url')
        .then(function (response) {
            
            // handle success
            const uri = response.data;
            console.log(uri)
            // Redirect the user to the obtained URI
            window.location.href = uri;
        })
        .catch(function (error) {
            // handle error
            console.log(error);
            alert('Impossible de se connecter avec Google pour le moment :/')
        })
        /*.finally(function () {
            // always executed
        });*/
    }

    return(
        <a onClick={getGoogleOAuthHref} href="#">
            Login with Google
        </a>
    )
}
export default GoogleLink;
