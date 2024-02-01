import React, { useState } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
function PostIt({scale, postItData, pageDimensions}){

    const postItDimensions = {width: 220, height: 190};
    const innerDimensions = {headerHeight: 30, contentHeight: 154}


    const [postIt, setPostIt] = useState({
        //Permet de centrer le post-it horizontalement par défaut (moitié de l'écran moins la largeur du post-it)
        x: postItData.positionX ? postItData.positionX : (pageDimensions.width/2)-(postItDimensions.width/2),
        //Permet de centrer le post-it verticalement par défaut (moitié de l'écran moins la hauteur du post-it)
        y: postItData.positionY ? postItData.positionY : (pageDimensions.height/2)-((innerDimensions.headerHeight + innerDimensions.contentHeight)/2),
        content: postItData.content
    });

    const handleStart = (() => {

    });
    
    const handleDrag = (() => {

    });

    const handleStop = ((e) => {
        setPostIt({
            ...postIt,
            x: e.screenX,
            y: e.screenY
        })
        //Sauvegarder la position avec un fetch en BDD
        console.log(postIt)
    });

    return(
        <Draggable
            //La poignée
            handle=".post-it-container"
            defaultPosition={
                {
                    x: postIt.x,
                    y: postIt.y
                }
            }
            grid={[1, 1]}
            scale={scale}
            onStart={handleStart}
            onDrag={handleDrag}
            onStop={handleStop}
        >
            <div className="panning-disabled post-it-container" style={{width: postItDimensions.width+'px'}}>
                <div className="post-it-header panning-disabled" style={{height: innerDimensions.headerHeight+'px'}}>

                </div>

                <div className="post-it-content panning-disabled" style={{minHeight: innerDimensions.contentHeight+'px'}}>
                    {postIt.content}
                </div>
            </div>
        </Draggable>


    );
}

export default PostIt;