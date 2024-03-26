import React from 'react';
import '../../../../styles/Home/HomeSection2/homeSection2.css';
import DiscordLink from '../../../layouts/Header/OAuth/DiscordLink';
import GoogleLink from '../../../layouts/Header/OAuth/GoogleLink';
import ClickIcon from '../../../../img/home/click-icon.png';
import LargeContainer from '../../../reusable/LargeContainer.js';
import RoleChecker from '../../../RoleChecker.js';
import { NavLink } from 'react-router-dom';



const HomeSection1 = () => {

    return(
        <LargeContainer className='home-section1-container'>
            <div id="home-login-wrapper">
                <span>
                    <RoleChecker roles={['ROLE_USER']}>
                        Accéder à l'application
                    </RoleChecker>

                    <RoleChecker removeIfLoggedIn={true}>
                        Se connecter en un clic <img src={ClickIcon} alt="pointer icon" />
                    </RoleChecker>
                    
                </span>
                <div id="home-login-links">
                    <RoleChecker roles={['ROLE_USER']}>
                        <NavLink id="access-btn" to={'/my-walls'}>Accès</NavLink>  
                    </RoleChecker>

                    <RoleChecker removeIfLoggedIn={true}>
                        <DiscordLink />
                        <GoogleLink />
                    </RoleChecker>
                    
                </div>
            </div>
        </LargeContainer>
    )
}
export default HomeSection1;