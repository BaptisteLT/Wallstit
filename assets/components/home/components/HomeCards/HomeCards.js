import React from 'react';
import '../../../../styles/Home/HomeCards/homeCards.css';
import HomePostIt from '../HomeCards/components/HomePostIt';

const HomeCards = () => {

    return(
        <div className='home-cards-row'>
            
            <h2>Une interface intuitive qui permet de créer des post-its et gérer des deadlines facilement.</h2>

            <div className="home-cards">
                <HomePostIt color="yellow" />
                <HomePostIt color="blue" />             
            </div>
        </div>
    )
}
export default HomeCards;
