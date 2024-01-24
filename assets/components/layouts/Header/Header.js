import React, { useContext } from 'react';
import GoogleLink from './GoogleLink';
import GitHubLink from './GitHubLink';
import Logo from '../../layouts/Logo';
import Separator from '../../layouts/Header/Separator';
import NavigationLink from './NavigationLink';

import '../../../styles/header/header.css';
import { AuthContext } from '../../useAuth';
import RoleChecker from '../../RoleChecker';


function Header() {

    const { user } = useContext(AuthContext);  // Destructure the context value correctly

    return(
        <header>
            <nav>
                <div>
                    <Logo />
                    <Separator />
                    
                    <NavigationLink to="/">Home</NavigationLink>
                    
                    <RoleChecker roles={['ROLE_USER']}>
                        <NavigationLink to="/my-walls">My Walls</NavigationLink>
                        <NavigationLink to="/post-it-priority">Task Priority</NavigationLink>
                    </RoleChecker>
                </div>
            
                <div>
                    <GitHubLink />
                    <RoleChecker removeIfLoggedIn={true}>
                        <GoogleLink />
                    </RoleChecker>
                    TODO logout
                </div>
            </nav>
        </header>
    )
}
export default Header;
