import React, { useState } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
function PostIt({ scale, pageDimensions, color, content, deadline, fontSizePixels, positionX, positionY, size, uuid })
{
    const { postItDimensions, innerDimensions } = getDimensionsFromSize(size);

    //Les datas du post-it
    const [postIt, setPostIt] = useState({
        //Permet de centrer le post-it horizontalement par défaut (moitié de l'écran moins la largeur du post-it)
        positionX: positionX ? positionX : (pageDimensions.width/2)-(postItDimensions.width/2),
        //Permet de centrer le post-it verticalement par défaut (moitié de l'écran moins la hauteur du post-it)
        positionY: positionY ? positionY : (pageDimensions.height/2)-((innerDimensions.headerHeight + innerDimensions.contentHeight)/2),
        fontSizePixels: fontSizePixels,
        content: content,
        color: color,
        deadline: deadline,
        size: size,
        uuid: uuid
    });

    /**
     * @param {string} sizeString - Size of the post-it ('small', 'large', or default is 'medium').
     * @returns {{ postItDimensions: {width: number, height: number}, innerDimensions: {headerHeight: number, contentHeight: number} }} - Object containing post-it and inner dimensions.
     */
    function getDimensionsFromSize(sizeString){

        let dimensions = {};

        switch (sizeString) {
            case 'small':
                dimensions = {
                    postItDimensions: {width: 180, height: 170},
                    innerDimensions: {headerHeight: 30, contentHeight: 134}
                };
                break;
            case 'large':
                dimensions = {
                    postItDimensions: {width: 240, height: 210},
                    innerDimensions: {headerHeight: 30, contentHeight: 174}
                };
                break;
            //Medium size by default
            default:
                dimensions = {
                    postItDimensions: {width: 210, height: 190},
                    innerDimensions: {headerHeight: 30, contentHeight: 154}
                };
        }
        return dimensions;
    }

    const handleStart = (() => {

    });
    
    const handleDrag = (() => {

    });

    const handleStop = ((e) => {
        setPostIt({
            ...postIt,
            positionX: e.screenX,
            positionY: e.screenY
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
                    x: postIt.positionX,
                    y: postIt.positionY
                }
            }
            grid={[1, 1]}
            scale={scale}
            onStart={handleStart}
            onDrag={handleDrag}
            onStop={handleStop}
        >
            <div className="panning-disabled post-it-container" style={{width: postItDimensions.width+'px'}}>
                <div className={"panning-disabled header-"+color} style={{height: innerDimensions.headerHeight+'px'}}>

                </div>

                <div className={"post-it-content panning-disabled content-"+color} style={{minHeight: innerDimensions.contentHeight+'px'}}>
                    {postIt.content}
                </div>
            </div>
        </Draggable>


    );
}

export default PostIt;