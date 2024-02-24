import React from 'react';
import '../../../../styles/Home/HomeCards/homeCards.css';
import HomePostIt from '../HomeCards/components/HomePostIt';

const HomeCards = () => {

    return(
        <div id="home-cards-wrapper">
            <div className='home-cards-row'>
                
                <HomePostIt color="yellow" />
                <h2>
                    Une interface intuitive qui permet de créer des post-its et gérer des deadlines facilement.
                    <br/>
                    <br/>
                    Le tout étant un projet complètement OpenSource
                </h2>
                <HomePostIt color="blue" />
            </div>
        </div>
    )
}
export default HomeCards;
