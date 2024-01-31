import React, { useState } from 'react';
import { TransformWrapper, TransformComponent } from "react-zoom-pan-pinch";
import '../../../styles/Wall/zoom.css';
import Tools from '../components/Tools';
import SideBar from './SideBar';

function Zoom({initialScale, handleTransform, pageDimensions, handleAddPostIt, children}) {


    //Cette fonction permet de récupérer le scale pour le passer au post-it afin que le curseur de la souris s'adapte au scale actuel quand on déplace un post-it


    return (
        <TransformWrapper
            initialScale={initialScale}
            //Centrer à l'initialisation
            centerOnInit={true}
            //On ne peut pas sortir des limites
            limitToBounds={true}
            //On désactive le fait de pouvoir voir en dehors des limites
            alignmentAnimation={{sizeX:0, sizeY: 0}}
            //Défini jusqu'à quel zoomIn on peut aller
            maxScale={5}
            //Défini jusqu'à quel zoomOut on peut aller
            minScale={0.6}
            panning={{excluded: ['panning-disabled']}}
            onTransformed={handleTransform}
        >
            {({ zoomIn, zoomOut, centerView }) => (
                /*Pour que le tools se retrouve dans la grid*/
                <div style={{position: 'relative'}}>
                    <Tools zoomIn={zoomIn} zoomOut={zoomOut} centerView={centerView} />

                    <SideBar addPostIt={handleAddPostIt} />

                    <TransformComponent
                    //Style du composant TransformWrapper
                    wrapperStyle={{
                        width: "100vw",
                        /*On enlève la hauteur du header et footer*/
                        height: "calc(100vh - 140px)",
                    }}>
                        <div style={{width: pageDimensions.width+'px', height: pageDimensions.height+'px'}}>
                            {children}
                        </div>
                    </TransformComponent>
                </div>
            )}
        </TransformWrapper>
    );
}

export default Zoom;