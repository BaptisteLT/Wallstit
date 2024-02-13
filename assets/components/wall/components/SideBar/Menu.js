import React from 'react';
import '../../../../styles/Wall/SideBar/menu.css';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import ArrowForwardIcon from '@mui/icons-material/ArrowForward';

function Menu({ children, collapsed, setCollapsed, sideBarSize })
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
        <div className={'menu-wrapper ' + menuSize() + (collapsed ? ' collapsed' : '')}>
            <div onClick={() => {setCollapsed(!collapsed)}} className={'collapse-btn ' + menuSize() + (collapsed ? ' collapsed' : '')}>
                {collapsed === false ? <ArrowBackIcon fontSize="medium" /> : <ArrowForwardIcon fontSize="medium" /> }
            </div>

            <div className={"menu "}>
                { children }
            </div>
        </div>
        
    );
}

export default Menu;