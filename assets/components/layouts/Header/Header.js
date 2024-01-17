import React from 'react';
import GoogleLink from './GoogleLink';
import GitHubLink from './GitHubLink';
import Logo from '../../layouts/Logo';
import Separator from '../../layouts/Header/Separator';
import NavigationLink from './NavigationLink';
import Auth from '../../Auth';
import '../../../styles/header/header.css';


function Header() {

    return(
        <header>
            <nav>
                <div>
                    <Logo />
                    <Separator />
                    
                    <NavigationLink to="/">Home</NavigationLink>
                    <Auth>
                        <NavigationLink to="/my-walls">My Walls</NavigationLink>
                    </Auth>
                    
                    <NavigationLink to="/post-it-priority">Task Priority</NavigationLink>
                </div>
            
                <div>
                    <GitHubLink />
                    <GoogleLink />
                </div>
            </nav>
        </header>
    )
}
export default Header;
