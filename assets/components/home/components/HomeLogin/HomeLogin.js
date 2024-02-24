import React from 'react';
import '../../../../styles/Home/HomeLogin/homeLogin.css';
import DiscordLink from '../../../layouts/Header/OAuth/DiscordLink';
import GoogleLink from '../../../layouts/Header/OAuth/GoogleLink';
import ClickIcon from '../../../../img/home//click-icon.png';

const HomeLogin = () => {

    return(
        <div id="home-login-wrapper">
            <span>Se connecter en un clic <img src={ClickIcon} alt="pointer icon" /></span>
            <div id="home-login-links">
                <DiscordLink />
                <GoogleLink />
            </div>
        </div>
    )
}
export default HomeLogin;
