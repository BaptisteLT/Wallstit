import React, { useState } from "react";

import SizeInput from './SizeInput';
import ContentInput from './ContentInput';
import ColorInput from './ColorInput';
import TitleInput from './TitleInput';

function SubMenuContent({ uuid, title, content, size, color })
{
    const [postItDataCallback, setPostItDataCallback] = useState(null);//TODO: 2.5 secondes sans modif on envoie le call API

    const handlePostItChange = (title = null, content = null, size = null, color = null) => {


        //setPostIts
        //TODO: setPostIts.filter(..., Il faut mettre à jour le postIt qui se situe dans setPostIts, il me faudra le uuid aussi
        //title)

        // Clear the timeout if it exists
        if (postItDataCallback) {
            clearTimeout(postItDataCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PATCH qui contiendra la positionX et positionY.
        //Si l'utilisateur déplace le post-it avant les X secondes, on clear le timeout et on le relance.
        const newTimeoutCallback = setTimeout(() => {
            //Sauvegarder la position en BDD
            //updatePostItInDB(title, content, size, color); TODO: à implémtener
            alert('it does work!' + uuid);
        }, 2500);

        // Store the callback in the state
        setPostItDataCallback(newTimeoutCallback);
    };


    return(
        <div className="main_wrapper">
            <TitleInput title={title} handlePostItChange={handlePostItChange} />
            <ContentInput content={content} handlePostItChange={handlePostItChange} />
            <SizeInput size={size} handlePostItChange={handlePostItChange} />
            <ColorInput color={color} handlePostItChange={handlePostItChange} />
        </div>
    )
}

export default SubMenuContent;