import React from 'react';
import '../../../../styles/Wall/SideBar/menu.css';


function Menu({ children, collapsed, sideBarSize })
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
        <div>
            <p>Collapse</p>
            <div className={"menu " + menuSize() + (collapsed ? ' collapsed' : '')}>
                { children }
            </div>
        </div>
        
    );
}

export default Menu;