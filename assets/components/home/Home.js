import React, { useLayoutEffect } from 'react';
import Container from "../reusable/Container";
import '../../styles/Home/home.css';
import HomeHeader from './components/HomeHeader/HomeHeader';
import HomeCards from './components/HomeCards/HomeCards';
import HomeLogin from './components/HomeLogin/HomeLogin';

const Home = () => {

    //Changing the body background-color before the page is loaded
    useLayoutEffect(() => {
        document.body.style.backgroundColor = "rgb(255, 251, 241)";
        //Remove background-color when unmounted
        return () => {
            document.body.style.removeProperty('background-color');
        }
    });

    return(
        <Container className="homepage">
            <HomeHeader />
            <HomeCards />
            <HomeLogin />
        </Container>
    )
}
export default Home;
