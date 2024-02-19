import React from 'react';
import '../../../../styles/Header/discordLink.css';
import {getOAuthHref} from './utils/oauthUtils';
import DiscordLogo from "../../../../img/header/discord-logo.svg";

function DiscordLink()
{
    const handleClick = (e) => {
        e.preventDefault();
        getOAuthHref('discord');
    };

    return(
        <a onClick={handleClick} target="#" className="provider-wrapper">
            <img id="discordLogo" src={DiscordLogo} />
            <div>Sign in with Discord</div>
        </a>
    )
}
export default DiscordLink;
