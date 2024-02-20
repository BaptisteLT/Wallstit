import React, { useEffect, useState, memo } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';
import CountDown from "./CountDown";
import SettingsIcon from '@mui/icons-material/Settings';
import { getDimensionsFromSize, updatePositionInDB, updateDeadlineDoneInBD } from '../utils/postItUtils';
import { usePostItContext } from '../PostItContext';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
const PostIt = React.memo(({ title, color, content, deadline, positionX, positionY, size, uuid, scale, deadlineDone, pageDimensions }) =>
{
    const { openPostItMenu, updatePostIt } = usePostItContext();

    const { postItDimensions, innerDimensions } = getDimensionsFromSize(size);

    //Les data du post-it
    const [postIt, setPostIt] = useState({
        //Permet de centrer le post-it horizontalement par défaut (moitié de l'écran moins la largeur du post-it)
        positionX: positionX ? positionX : (pageDimensions.width/2)-(postItDimensions.width/2),
        //Permet de centrer le post-it verticalement par défaut (moitié de l'écran moins la hauteur du post-it)
        positionY: positionY ? positionY : (pageDimensions.height/2)-((innerDimensions.headerHeight + innerDimensions.contentHeight)/2)
    });
    
    //Largeur et hauteur du post-it de base
    const [dimensions, setDimensions] = useState(getDimensionsFromSize(size));
    //Permet de stocker le callback du setTimeout afin de pouvoir l'annuler
    const [positionTimeoutCallback, setPositionTimeoutCallback] = useState(null);

    const [deadlineDate, setDeadlineDate] = useState(null);
    //Dès que postIt.size change, on va mettre à jour la taille du postIt en fonction de si c'est "small", "medium" ou "large"
    useEffect(() => {
        setDimensions(getDimensionsFromSize(size));
    }, [size]);

    useEffect(() => {
        setDeadlineDate(deadline ? new Date(deadline) : null);
    }, [deadline]);
    

    const handleStop = ((e, ui) => {
        const positionX = parseInt(ui.x);
        const positionY = parseInt(ui.y);

        //Update the Post-It position in the DOM
        setPostIt({
            ...postIt,
            positionX: positionX,
            positionY: positionY
        });

        // Clear the timeout if it exists
        if (positionTimeoutCallback) {
            clearTimeout(positionTimeoutCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PATCH qui contiendra la positionX et positionY.
        //Si l'utilisateur déplace le post-it avant les X secondes, on clear le timeout et on le relance.
        const newTimeoutCallback = setTimeout(() => {
            //Sauvegarder la position en BDD
            updatePositionInDB(uuid, positionX, positionY);
        }, 2500);

        // Store the callback in the state
        setPositionTimeoutCallback(newTimeoutCallback);
    });

    const updateDeadlineDone = (isDeadlineDone) => {

        let data = {};
        data.deadlineDone = isDeadlineDone;

        //Mise à jour dans le DOM
        updatePostIt(data, uuid);
        //Mise à jour en BDD
        updateDeadlineDoneInBD(uuid, isDeadlineDone);
    }

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
            onStop={handleStop}
        >
            <div className="panning-disabled post-it-container" style={{width: dimensions.postItDimensions.width+'px'}}>

                <div className={`panning-disabled header header-${color}`} style={{height: dimensions.innerDimensions.headerHeight+'px'}}>
                    <SettingsIcon onClick={() => openPostItMenu(uuid)} fontSize="medium" className="panning-disabled edit-icon" />
                </div>

                <div className={`post-it-content panning-disabled content-${color}`} style={{minHeight: dimensions.innerDimensions.contentHeight+'px'}}>
                    {
                        deadlineDate !== null &&
                        <div className="deadline-wrapper">
                            <CountDown updateDeadlineDone={updateDeadlineDone} deadlineDone={deadlineDone} deadline={deadlineDate} className={"panning-disabled deadline"} />
                        </div>
                    }
                    <p className="panning-disabled title">{title}</p>
                    <p className="panning-disabled content">{content}</p>
                </div>
            </div>
        </Draggable>
    );
  });



export default PostIt;