import React, {useState} from 'react';
import { NavLink } from 'react-router-dom';



function NavigationLink({to, children})
{
    return(
        <NavLink 
            className={({ isActive }) =>
                isActive ? "active navigationLink" : "navigationLink"
            } 
            to={to}>
            {children}
        </NavLink>  
    )
}

export default NavigationLink;
