import React from 'react';
import '../../../../styles/Home/HomeHeader/homeHeader.css';
import mainImg from '../../../../img/home/site.png';
   
const HomeHeader = () => {

    return(
        <>
            <div id="home-header-wrapper">
                <h1>Il n'a jamais été aussi facile de mettre ses idées au clair.</h1>
                <h2 className="home-header-h2">Concevez rapidement vos murs de post-its. Organisez et planifiez vos tâches en toute simplicité à l'aide d'une interface épurée, configurable et ludique.</h2>
                <button id="start-using-btn">Commencez à utiliser GRATUITEMENT</button>
            </div>

            <img id="main-img" src={mainImg} alt="Application layout" />
        </>
    )
}
export default HomeHeader;
