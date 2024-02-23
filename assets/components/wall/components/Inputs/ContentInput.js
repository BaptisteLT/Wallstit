import React from "react";
import '../../../../styles/Wall/Inputs/contentInput.css';
import '../../../../styles/Wall/Inputs/generalInput.css';

function ContentInput({ label, content, handleChange })
{
     
    const handleOnChange = (event) => {
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handleChange('content', event.target.value);
    };

    return(
        <div>
            <label className="label" htmlFor="content">{label}</label>
            <textarea onChange={handleOnChange} defaultValue={content} className="input" id="content" name="content" rows="5"></textarea>
        </div>
    )
}

export default ContentInput;