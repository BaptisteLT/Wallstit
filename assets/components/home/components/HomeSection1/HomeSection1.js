import React from 'react';
import '../../../../styles/Home/HomeSection1/homeSection1.css';
import ApplicationDemoImage from '../../../../img/home/animated.gif';
import Container from '../../../reusable/Container';
   
const HomeSection1 = () => {

    return(
        <Container className='section1'>
            <div id="section-left">
                <h1>Il n'a jamais été aussi facile de mettre ses idées au clair.</h1>
                <h2>Concevez rapidement vos murs de post-its. <span className='bold'>Organisez et planifiez vos tâches en toute simplicité</span> à l'aide d'une interface épurée, configurable et ludique.</h2>
                <button id="start-using-btn">UTILISER GRATUITEMENT</button>
            </div>

            <div id="section-right">
                <img src={ApplicationDemoImage} alt="application demo"></img>
            </div>
        </Container>
    )
}
export default HomeSection1;
