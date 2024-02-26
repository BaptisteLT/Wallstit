import React, { useState } from 'react';
import '../../../styles/Header/accountMenu.css';
import { NavLink } from 'react-router-dom';
import LogoutIcon from '@mui/icons-material/Logout';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';

const AccountMenu = () => {

    const [profileOpen, setProfileOpen] = useState(false)

    const handleMenuOpen = () => {
        setProfileOpen(!profileOpen);
    };

    const handleLogout = () => {
        //TODO: API call to delete the refresh token + delete session + cookies + user in local storage
    };

    

    return(
        <div id='account-menu'>
            <div onClick={handleMenuOpen} id='logo'></div>
            <div onClick={handleMenuOpen} id="menu-arrow" style={{display: profileOpen ? 'block' : 'none'}}></div>
            <div onClick={handleMenuOpen} id="menu" style={{display: profileOpen ? 'block' : 'none'}}>
                <NavLink to={'/my-account'}><span><span>My account</span> <AccountCircleIcon /></span></NavLink>  
                <hr />
                <a href='#' onClick={handleLogout}><span><span>Log out</span> <LogoutIcon /></span></a>
            </div>
        </div>
    )
}
export default AccountMenu;
