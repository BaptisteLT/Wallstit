import React from 'react';
import '../../../../styles/Wall/SideBar/menu.css';


function Menu({ children, sideBarSize })
{
    function menuSize()
    {
        //Si rien n'est spécifié on met en taille medium par défaut
        if(sideBarSize === null)
        {
            return 'medium';
        }
        return sideBarSize;
    }

    return(
        <div className={"menu " + menuSize()}>
            { children }
        </div>
    );
}

export default Menu;