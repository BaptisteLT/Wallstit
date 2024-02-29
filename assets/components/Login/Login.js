import React from "react";
import LargeContainer from "../reusable/LargeContainer";
import '../../styles/Login/login.css';
import DiscordLink from "../layouts/Header/OAuth/DiscordLink";
import GoogleLink from "../layouts/Header/OAuth/GoogleLink";

function Login()
{
    return(
        <LargeContainer className="login-container">
            <div id="login-wrapper">
                <span>Se connecter</span>
                <DiscordLink />
                <GoogleLink />

            </div>
        </LargeContainer>
    );
}

export default Login;