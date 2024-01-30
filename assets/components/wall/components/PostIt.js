import React, { useState } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
function PostIt({scale, pageDimensions}){


    const postItDimensions = {width: 220, height: 190};
    const innerDimensions = {headerHeight: 30, contentHeight: 154}


    const [position, setPosition] = useState({
        //Permet de centrer le post-it horizontalement par défaut (moitié de l'écran moins la largeur du post-it)
        x: (pageDimensions.width/2)-(postItDimensions.width/2),
        //Permet de centrer le post-it verticalement par défaut (moitié de l'écran moins la hauteur du post-it)
        y: (pageDimensions.height/2)-((innerDimensions.headerHeight + innerDimensions.contentHeight)/2) 
    });

    const handleStart = (() => {

    });
    
    const handleDrag = (() => {

    });

    const handleStop = ((e) => {
        setPosition({
            x: e.screenX,
            y: e.screenY
        })
        //Sauvegarder avec un fetch en BDD
        console.log(position)
    });

    return(
        <Draggable
            
            //La poignée
            handle=".post-it"
            defaultPosition={
                {
                    x: position.x,
                    y: position.y
                }
            }
            grid={[2, 2]}
            scale={scale}
            onStart={handleStart}
            onDrag={handleDrag}
            onStop={handleStop}>
                <div className="panning-disabled post-it" style={{width: postItDimensions.width+'px'}}>
                    <div className="post-it-header panning-disabled" style={{height: innerDimensions.headerHeight+'px'}}>

                    </div>

                    <div className="post-it-content panning-disabled" style={{height: innerDimensions.contentHeight+'px'}}>
                        the panning-disabled is required even in the children elements apparently
                    </div>
                </div>
        </Draggable>


    );
}

export default PostIt;