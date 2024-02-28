import React, { useLayoutEffect } from 'react';
import '../../styles/Home/home.css';
import HomeSection1 from './components/HomeSection1/HomeSection1';
import HomeSection2 from './components/HomeSection2/HomeSection2';
import HomeSection3 from './components/HomeSection3/HomeSection3';
import HomeSection4 from './components/HomeSection4/HomeSection4';

const Home = () => {

    //Changing the body background-color before the page is loaded
    useLayoutEffect(() => {
        document.body.style.backgroundColor = "#FBFBFB";
        //Remove background-color when unmounted
        return () => {
            document.body.style.removeProperty('background-color');
        }
    });

    //TODO: refaire nouveau canva: https://www.canva.com/design/DAF95re7GPE/TSi_8kr8-77wjsjhlPa5_w/edit?utm_content=DAF95re7GPE&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton

    return(
        <div className="homepage">
            <HomeSection1 />
            <HomeSection2 />
            <HomeSection3 />
            <HomeSection4 />
        </div>
    )
}
export default Home;
