import React from 'react';
import { Link } from 'react-router-dom';
import GoogleLink from './GoogleLink';
import '../../styles/header.css';

function Header() {

    return(
        <header>
            <div>
                <nav>
                    <Link to="/">Home</Link>
                    <Link to="my-walls">My walls</Link>
                    <Link to="post-it-priority">Post-It Priority</Link>
                </nav>
            </div>
           
        
            <div>
                <div>GitHub Code</div>
                <GoogleLink />
            </div>
        </header>
    )
}
export default Header;
