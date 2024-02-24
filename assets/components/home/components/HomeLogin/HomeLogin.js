import React from 'react';
import '../../../../styles/Home/HomeLogin/homeLogin.css';
import DiscordLink from '../../../layouts/Header/OAuth/DiscordLink';
import GoogleLink from '../../../layouts/Header/OAuth/GoogleLink';

const HomeLogin = () => {

    return(
        <div id="home-login-wrapper">
            <p>Connectez-vous dès maintenant!</p>
            <div>
                <DiscordLink />
                <GoogleLink />
            </div>
        </div>
    )
}
export default HomeLogin;
