import React, { useState } from "react";
import SizeInput from '../../Inputs/SizeInput';
import ContentInput from '../../Inputs/ContentInput';
import ColorInput from '../../Inputs/ColorInput';
import TitleInput from '../../Inputs/TitleInput';
import DeadlineInput from '../../Inputs/DeadlineInput';
import { updatePostItInDB } from '../../../utils/postItUtils';
import { usePostItContext } from '../../../PostItContext';


function PostItSubMenuContent({ uuid, title, content, size, color, deadline })
{
    const { updatePostIt } = usePostItContext();

    const [postItDataCallback, setPostItDataCallback] = useState(null);

    const handlePostItChange = async (title = null, content = null, size = null, color = null, deadline = null) => {

        let data = {};

        if (title !== null) data.title = title;
        if (content !== null) data.content = content;
        if (size !== null) data.size = size;
        if (color !== null) data.color = color;
        
        if (deadline !== null && deadline === '0000-00-00T00:00:00+00:00'){
            data.deadline = null;
        }
        else if(deadline !== null)
        {
            data.deadline = deadline;
        }

        const currentPostIt = await updatePostIt(data, uuid);

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
            <DeadlineInput deadline={deadline} handlePostItChange={handlePostItChange} />
            <SizeInput label='Size' size={size} handlePostItChange={handlePostItChange} />
            <ColorInput color={color} handlePostItChange={handlePostItChange} />
        </div>
    )
}

export default PostItSubMenuContent;