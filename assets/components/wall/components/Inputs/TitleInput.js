import React from "react";
import '../../../../styles/Wall/Inputs/titleInput.css';
import '../../../../styles/Wall/Inputs/generalInput.css';

function TitleInput({ label, title, handleChange })
{
     
    const handleOnChange = (event) => {
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handleChange('title', event.target.value);
    };

    return(
        <div>
            <label className="label" htmlFor="input_title">{label}</label>
            <input onChange={handleOnChange} className="input" type="text" name="title" id="input_title" defaultValue={title} />
        </div>
    )
}

export default TitleInput;