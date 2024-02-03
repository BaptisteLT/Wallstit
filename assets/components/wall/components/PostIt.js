import React, { useEffect, useState } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';
import CountDown from "./CountDown";
import { getDimensionsFromSize } from '../utils/postItUtils';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
function PostIt({ scale, pageDimensions, color, content, deadline, fontSizePixels, positionX, positionY, size, uuid })
{
    const { postItDimensions, innerDimensions } = getDimensionsFromSize(size);

    //Les data du post-it
    const [postIt, setPostIt] = useState({
        //Permet de centrer le post-it horizontalement par défaut (moitié de l'écran moins la largeur du post-it)
        positionX: positionX ? positionX : (pageDimensions.width/2)-(postItDimensions.width/2),
        //Permet de centrer le post-it verticalement par défaut (moitié de l'écran moins la hauteur du post-it)
        positionY: positionY ? positionY : (pageDimensions.height/2)-((innerDimensions.headerHeight + innerDimensions.contentHeight)/2),
        fontSizePixels: fontSizePixels,
        content: content,
        color: color,
        deadline: (deadline ? new Date(deadline) : null),
        size: size,
        uuid: uuid
    });

    //Largeur et hauteur du post-it de base
    const [dimensions, setDimensions] = useState(() => {
        return getDimensionsFromSize(size);
    });
      
    //Dès que postIt.size change, on va mettre à jour la taille du postIt en fonction de si c'est "small", "medium" ou "large"
    useEffect(() => {
        setDimensions(getDimensionsFromSize(postIt.size));
    }, [postIt.size]);

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
            <div className="panning-disabled post-it-container" style={{width: dimensions.postItDimensions.width+'px'}}>
                <div className={"panning-disabled header-"+color} style={{height: dimensions.innerDimensions.headerHeight+'px'}}>
                    <CountDown deadline={postIt.deadline} />
                </div>

                <div className={"post-it-content panning-disabled content-"+color} style={{minHeight: dimensions.innerDimensions.contentHeight+'px'}}>
                    {postIt.content}
                </div>
            </div>
        </Draggable>
    );
}

export default PostIt;