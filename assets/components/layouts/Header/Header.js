import React from 'react';
import GoogleLink from './GoogleLink';
import GitHubLink from './GitHubLink';
import NavigationLink from './NavigationLink';
import '../../../styles/header/header.css';

function Header() {

    return(
        <header>
            <nav>
                <div>
                    <NavigationLink to="/">Home</NavigationLink>
                    <NavigationLink to="/my-walls">My walls</NavigationLink>
                    <NavigationLink to="/post-it-priority">Task priority</NavigationLink>
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
