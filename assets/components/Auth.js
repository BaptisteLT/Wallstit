import React, { useEffect, useState } from "react";
import { jwtDecode } from "jwt-decode";
import Cookies from "js-cookie";
import axios from "axios";

function Auth({ allowedRoles, children })
{
    //Variable qui contriendra l'utilisateur récupéré du JWT
    const [roles, setRoles] = useState(['PUBLIC_ACCESS']);

    useEffect(() => {
        
        const token = Cookies.get('jwtToken');
        console.log(token);
        //Si un token est en session
        if(token){
            //Alors on le décode pour récupérer les informations de l'utilisateur situées à l'intérieur
            const decodedUser = jwtDecode(token);
            setUser(decodedUser);
        }
    }, []);

    async function getUserRoles()
    {

    }

    //Permet de cacher ou afficher le component enfant
    function userHasPermission(){
        //console.log(user);
        return false;
    }

    return(
        userHasPermission() ? <>{ children }</> : null
    )
}

export default Auth;