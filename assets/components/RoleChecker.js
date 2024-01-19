import React, { useContext } from "react";
import { AuthContext } from "./useAuth";

/**
 * Ce composant a pour mission d'afficher ou non des éléments en fonction du rôle de l'utilisateur dans l'application
 */
function RoleChecker({roles = [], children})
{
    const { user } = useContext( AuthContext );

    return(
        userHasPermission() ? <>{ children }</> : null
    )

    /*Vérifie que l'utilisateur a bien le role en question*/
    function userHasPermission()
    {
        //On regarde si user ou jwtPayload est null ou undefined, si c'est le cas on retourne []
        const userRoles = user?.jwtToken?.jwtPayload?.roles || [];
        
        //Va boucler sur roles et regarde si le role est dans userRoles, si c'est le cas on retourne true. Si aucun rôle n'est trouvé on retourne false
        return roles.some((role) => userRoles.includes(role));
    }


}

export default RoleChecker;