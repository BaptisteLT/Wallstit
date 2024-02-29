import React, { useEffect, useState, memo } from "react";
import '../../../styles/Wall/post-it.css'
import Draggable from 'react-draggable';
import CountDown from "./CountDown";
import SettingsIcon from '@mui/icons-material/Settings';
import { getDimensionsFromSize, updatePositionInDB, updateDeadlineDoneInBD } from '../utils/postItUtils';
import { usePostItContext } from '../PostItContext';
import DeleteConfirm from "../../reusable/DeleteConfirm";
import InitialDeleteIcon from "../../reusable/InitialDeleteIcon";
import { toast } from 'react-toastify';
import { DeleteForever } from "@mui/icons-material";
import axios from 'axios';

//Dragable: https://www.npmjs.com/package/react-draggable#controlled-vs-uncontrolled
const PostIt = React.memo(({ title, color, content, deadline, positionX, positionY, size, uuid, scale, deadlineDone, pageDimensions }) =>
{
    const { openPostItMenu, updatePostIt } = usePostItContext();

    const { postItDimensions, innerDimensions } = getDimensionsFromSize(size);

    const [isDeleteOpen, setIsDeleteOpen] = useState(false);

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

    //Permet de bloquer à 1 click les requêtes vers le serveur lorsque la personne clique sur le bouton de suppression rouge.
    const [isDeleteRequestProcessed, setIsDeleteRequestProcessed] = useState(false);

    //Défini si la carte est visible ou non (utilisé pour supprimer la carte)
    const [isPostItVisible, setIsPostItVisible] = useState(true);

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

        //On met à jour seulement si la position a changé
        if(ui.x != positionX || ui.y != positionY)
        {
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
            }, 2000);

            // Store the callback in the state
            setPositionTimeoutCallback(newTimeoutCallback);
                    
        }
    });

    const updateDeadlineDone = (isDeadlineDone) => {

        let data = {};
        data.deadlineDone = isDeadlineDone;

        //Mise à jour dans le DOM
        updatePostIt(data, uuid);
        //Mise à jour en BDD
        updateDeadlineDoneInBD(uuid, isDeadlineDone);
    }

    //Ouvre ou ferme la sidebar
    function handleDeleteOpen()
    {
        setIsDeleteOpen(!isDeleteOpen);
    }

    //Delete forever
    function handlePostItDeletion() 
    {
        //On regarde si une requête est déjà en cours pour éviter que la personne clique plusieurs fois par accident et que la personne reçoive 1 message success et plusieurs messages d'erreur par la suite.
        if(!isDeleteRequestProcessed)
        {
            setIsDeleteRequestProcessed(true);

            //TODO: supprimer le post-it de l'array d'objects Post-It sinon la sidebar reste (et par la même occasion il y aura surement plus besoin de fairesetIsPostItVisible(false) )
            
            axios.delete('/api/post-it/delete/'+uuid)
            .then(function(response){
              
                setIsPostItVisible(false);
                toast.success("Post-it removed successfully!")
            })
            .catch(function(error){
                console.log(error)
                toast.error('An error occured while trying to delete the post-it.');
            })
            .finally(function(){
                setIsDeleteRequestProcessed(false);
            });
        }
        
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
            //Disable drag on icons
            cancel='svg'
        >
            <div className="panning-disabled post-it-container" style={{width: dimensions.postItDimensions.width+'px', display: (isPostItVisible ? 'block' : 'none')}}>

                <div className={`panning-disabled header header-${color}`} style={{height: dimensions.innerDimensions.headerHeight+'px'}}>
                    <SettingsIcon onClick={() => openPostItMenu(uuid)} fontSize="medium" className="panning-disabled edit-icon" />
                    <InitialDeleteIcon className="initialDeleteIcon icon" handleDelete={handleDeleteOpen} />
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

                <DeleteConfirm handleDeleteMenuOpen={handleDeleteOpen} handleItemDelete={handlePostItDeletion} menuOpen={isDeleteOpen} />
            </div>
        </Draggable>
    );
  });



export default PostIt;