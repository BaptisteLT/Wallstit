import React from 'react';
import { TransformWrapper, TransformComponent } from "react-zoom-pan-pinch";
import '../../../styles/Wall/zoom.css';
import Tools from '../components/Tools';

function Zoom({children}) {
    return (
        <TransformWrapper
            initialScale={1}
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
        >
            {({ zoomIn, zoomOut, centerView }) => (
                /*Pour que le tools se retrouve dans la grid*/
                <div style={{position: 'relative'}}>
                    <Tools zoomIn={zoomIn} zoomOut={zoomOut} centerView={centerView} />
                    
                    <TransformComponent
                    //Style du composant TransformWrapper
                    wrapperStyle={{
                        width: "100vw",
                        /*On enlève la hauteur du header et footer*/
                        height: "calc(100vh - 140px)",
                    }}>
                        <div style={{width: '3840px', height: '2160px'}}>
                            {children}
                        </div>
                    </TransformComponent>
                </div>
            )}
        </TransformWrapper>
    );
}

export default Zoom;