import React from 'react';
import '../../../../styles/Home/HomeSection2/homeSection2.css';
import DiscordLink from '../../../layouts/Header/OAuth/DiscordLink';
import GoogleLink from '../../../layouts/Header/OAuth/GoogleLink';
import ClickIcon from '../../../../img/home/click-icon.png';
import LargeContainer from '../../../reusable/LargeContainer.js';

const HomeSection1 = () => {

    return(
        <LargeContainer className='home-section1-container'>
            <div id="home-login-wrapper">
                <span>Se connecter en un clic <img src={ClickIcon} alt="pointer icon" /></span>
                <div id="home-login-links">
                    <DiscordLink />
                    <GoogleLink />
                </div>
            </div>
        </LargeContainer>
    )
}
export default HomeSection1;