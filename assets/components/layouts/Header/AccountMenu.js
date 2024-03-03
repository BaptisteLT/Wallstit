import React, { useState, useContext } from 'react';
import '../../../styles/Header/accountMenu.css';
import { NavLink, useNavigate } from 'react-router-dom';
import LogoutIcon from '@mui/icons-material/Logout';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import axios from "axios";
import { AuthContext } from '../../useAuth';


const AccountMenu = () => {

    const { user, setUser } = useContext(AuthContext);

    const navigate = useNavigate();

    const [profileOpen, setProfileOpen] = useState(false)

    const handleMenuOpen = () => {
        setProfileOpen(!profileOpen);
    };

    // Deleting cookies and user in localStorage
    const handleLogout = () => {
        axios.post('/auth/logout')
        .then(function(){
            setUser(null);
            navigate('/');
        })
        .catch(function(error){
            toast.error(error.response?.data?.error || 'An error occurred');
        })
    };

    return(
        <div id='account-menu'>
            <div onClick={handleMenuOpen} style={{backgroundImage: 'url('+user.jwtToken.jwtPayload.avatarImg+')'}} id='logo'></div>
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
