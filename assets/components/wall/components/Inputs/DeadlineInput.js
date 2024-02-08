import React from "react";
import '../../../../styles/Wall/titleInput.css';
import '../../../../styles/Wall/generalInput.css';

function DeadlineInput({ deadline, handlePostItChange })
{

    const validDateFormat = () => {
        if (!deadline) {
            return '';
        }
        const formattedDate = (new Date(deadline)).toISOString().slice(0,16);

        return formattedDate;
    };
    

    const handleOnChange = (event) => {
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handlePostItChange(null, null, null, null, event.target.value);
    };

    return(
        <div>
            <label className="label" htmlFor="input_deadline">Deadline</label>
            <input onChange={handleOnChange} type="datetime-local" id="input_deadline" name="deadline" className="input" defaultValue={validDateFormat()} />
        </div>
    )
}

export default DeadlineInput;