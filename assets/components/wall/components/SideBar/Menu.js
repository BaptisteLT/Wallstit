import React from 'react';
import '../../../../styles/Wall/SideBar/menu.css';


function Menu({ children })
{
    return(
        <div className="menu">
            { children }
        </div>
    );
}

export default Menu;