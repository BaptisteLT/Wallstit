import React from 'react';

import '../../../../styles/Home/HomeSection4/homeSection4.css';
import LargeContainer from '../../../reusable/LargeContainer';
import Container from '../../../reusable/Container';
import HomePostIt from '../HomeSection4/components/HomePostIt';
import GitHubLink from '../../../layouts/Header/GitHubLink';

const HomeSection4 = () => {

    return(
        <LargeContainer className='home-section4-large-container'>
            <Container className='home-section4-container'>
                <HomePostIt color="yellow" />
                <div>
                    <h2>Projet Open-Source</h2>
                    <h3>Nous offrons une <span className='bold'>transparence la plus totale</span>. Le code est disponible en ligne et toute contribution est la bienvenue.</h3>
                    <h3>Il vous est ainsi possible de voir comment sont traitées vos données. Le code est accessible sur Github depuis le lien suivant.</h3>
                    <GitHubLink />
                </div>
  
            </Container>
        </LargeContainer>
    )
}
export default HomeSection4;