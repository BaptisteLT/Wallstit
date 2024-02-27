import React from 'react';
import '../../../../styles/Home/HomeSection3/homeSection3.css';
import Container from '../../../reusable/Container';
import OfferText from '../HomeSection3/components/OfferText';
   
const HomeSection3 = () => {

    return(
        <Container className='section3'>
            <h2>What we have to offer</h2>
            
            <div className='sections-wrapper'>
                <div className='section-left'>
                    <OfferText>
                        Cross-platform: <span className="bold">Utilisable sur tous supports</span> et systèmes d’exploitation. (Windows, MacOS, Linux, Android, iOS)
                    </OfferText>

                    <OfferText>
                        Interface <span className="bold">simple et intuitive.</span>
                    </OfferText>

                    <OfferText>
                        <span className="bold">100% gratuit</span> et le sera toujours.
                    </OfferText>

                    <OfferText>
                        Gérez des deadlines: <span className="bold">ne ratez plus jamais de tâches importantes.</span>
                    </OfferText>
                </div>

                <div className='section-right'>
                    <OfferText>
                        <span className="bold">Faites des économies</span>, et aidez à <span className="bold">préserver nos forêts.</span> ♻️
                    </OfferText>

                    <OfferText>
                        Développé avec les technologies les plus récentes pour une <span className="bold">expérience</span> et une <span className="bold">fluidité optimale</span>.
                    </OfferText>

                    <OfferText>
                        Protection de la vie privée: Seul un pseudonyme est requis pour utiliser l’application. <span className="bold">Aucun cookie de traçage ou de pubs intrusives</span> ne seront jamais mis en place.
                    </OfferText>
                </div>
            </div>

        </Container>
    )
}
export default HomeSection3;
