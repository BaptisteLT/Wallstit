import React, { useState } from "react";
import SizeInput from './SizeInput';
import ContentInput from './ContentInput';
import ColorInput from './ColorInput';
import TitleInput from './TitleInput';
import { updatePostItInDB } from '../utils/postItUtils';

function SubMenuContent({ uuid, title, content, size, color, setPostIts, postIts })
{
    const [postItDataCallback, setPostItDataCallback] = useState(null);

    const handlePostItChange = (title = null, content = null, size = null, color = null) => {
        let currentPostIt = null;
        let data = {};

        if (title !== null) data.title = title;
        if (content !== null) data.content = content;
        if (size !== null) data.size = size;
        if (color !== null) data.color = color;

        //Application des modifications sur le PostIt concerné
        setPostIts(postIts.map(postIt => {
            if(postIt.uuid === uuid){
                // Create a *new* object with changes
                const newPostIt = { ...postIt, ...data };
                currentPostIt = newPostIt;
                return newPostIt;
            } else {
              // No changes
              return postIt;
            }
        }));

        //Dans le cas où le PostIt ne serait pas trouvé dans l'array on return même si c'est very unlikely
        if(currentPostIt === null) return;

        // Clear the timeout if it exists
        if (postItDataCallback) {
            clearTimeout(postItDataCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PATCH qui contiendra la positionX et positionY.
        //Si l'utilisateur déplace le post-it avant les X secondes, on clear le timeout et on le relance.
        const newTimeoutCallback = setTimeout(() => {
            //Sauvegarder la position en BDD
            updatePostItInDB(currentPostIt);
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