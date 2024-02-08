import React from "react";
import '../../../../styles/Wall/titleInput.css';
import '../../../../styles/Wall/generalInput.css';

function TitleInput({ title, handlePostItChange })
{
     
    const handleOnChange = (event) => {
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handlePostItChange(event.target.value);
    };

    return(
        <div>
            <label className="label" htmlFor="input_title">Title</label>
            <input onChange={handleOnChange} className="input" type="text" name="title" id="input_title" defaultValue={title} />
        </div>
    )
}

export default TitleInput;