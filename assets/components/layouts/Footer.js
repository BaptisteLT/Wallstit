import React from 'react';
import '../../styles/Footer/main.css';
import Container from '../reusable/Container';
import { NavLink } from 'react-router-dom';
import RoleChecker from '../RoleChecker';

const Footer = () => {
    return(
        <footer>
            <Container>
                <div id="footer-content-wrapper">
                    <div>
                        <ul>
                            Navigation
                            <li><NavLink to={'/'}>Home</NavLink></li>
                            <RoleChecker roles={['ROLE_USER']}>
                                <li><NavLink to={'/my-walls'}>Walls</NavLink></li>
                            </RoleChecker>
                            <RoleChecker roles={['ROLE_USER']}>
                                <li><NavLink to={'/my-account'}>Mon compte</NavLink></li>
                            </RoleChecker>
                        </ul>
                    </div>

                    <div>
                        <p>2024 © Wallstit.</p>
                    </div>

                    <div>
                        <ul>
                            <li><NavLink to={'/legal-notices'}>Mentions légales</NavLink></li>
                            <li><NavLink to={'/personal-data'}>Données personnelles</NavLink></li>
                            <li><NavLink to={'/cookies'}>Cookies</NavLink></li>
                        </ul>
                    </div>
                </div>
                
                
               
            </Container>
        </footer>
    )
}
export default Footer;
