import React, { useContext } from 'react';
import GoogleLink from './OAuth/GoogleLink';
import DiscordLink from './OAuth/DiscordLink';
import GitHubLink from './GitHubLink';
import Logo from '../../layouts/Logo';
import Separator from '../../layouts/Header/Separator';
import NavigationLink from './NavigationLink';

import '../../../styles/header/header.css';
import RoleChecker from '../../RoleChecker';


function Header() {

    return(
        <header>
            <nav>
                <div>
                    <Logo />
                    <Separator />
                    
                    <NavigationLink to="/">Home</NavigationLink>
                    
                    <RoleChecker roles={['ROLE_USER']}>
                        <NavigationLink to="/my-walls">Walls</NavigationLink>
                        <NavigationLink to="/post-it-priority">Prioritize</NavigationLink>
                    </RoleChecker>
                </div>
            
                <div>
                    <GitHubLink />
                    <RoleChecker removeIfLoggedIn={true}>
                        <DiscordLink />
                        <GoogleLink />
                    </RoleChecker>
                    TODO logout
                </div>
            </nav>
        </header>
    )
}
export default Header;
