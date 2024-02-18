import React from 'react';
import '../../../../styles/Header/linkeInLink.css';
import {getOAuthHref} from './utils/oauthUtils';
import LinkedInLogo from "../../../../img/header/linkedin-logo.png";

function LinkedinLink()
{
    const handleClick = (e) => {
        e.preventDefault();
        getOAuthHref('linkedin');
    };

    return(
        <a onClick={handleClick} target="#" className="provider-wrapper">
            <img id="linkedInLogo" src={LinkedInLogo} />
            <div>Sign in with LinkedIn</div>
        </a>
    )
}
export default LinkedinLink;
