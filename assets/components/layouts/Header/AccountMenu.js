import React, { useState } from 'react';
import '../../../styles/Header/accountMenu.css';
import { NavLink } from 'react-router-dom';


const AccountMenu = () => {

    const [profileOpen, setProfileOpen] = useState(false)

    const handleProfileClick = () => {
        setProfileOpen(!profileOpen);
    };

    const handleLogout = () => {
        //TODO: API call to delete the refresh token + delete session + cookies + user in local storage
    };

    return(
        <div id='account-menu'>{/*Position relatice*/}
            <div onClick={handleProfileClick} id='logo'></div>
            <div id="menu-arrow" style={{display: profileOpen ? 'block' : 'none'}}></div>
            <div id="menu" style={{display: profileOpen ? 'block' : 'none'}}>
                
                    <NavLink 
                        to={'/my-account'}>
                        <span>My account</span>
                    </NavLink>  
    
                
                <hr />
                <a href='#' onClick={handleLogout}><span>Log out</span></a>
            </div>
        </div>
    )
}
export default AccountMenu;
