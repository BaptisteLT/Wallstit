
import axios from "axios";
import { toast } from 'react-toastify';

function getOAuthHref(provider)
{
    //Request to the controller
    axios.get('/auth/get-' + provider.toLowerCase() + '-oauth2-url')
    .then(function (response) {
        // handle success
        const uri = response.data;
        // Redirect the user to the obtained URI
        window.location.href = uri;
    })
    .catch(function (error) {
        // handle error
        toast.error(`Impossible to login with ${provider} at the moment.`)
    })
}

export {getOAuthHref}