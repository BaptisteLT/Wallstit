import React from "react";
import '../../../../styles/Wall/Inputs/titleInput.css';
import '../../../../styles/Wall/Inputs/generalInput.css';

function DeadlineInput({ deadline, handlePostItChange })
{

    const validDateFormat = () => {
        if (!deadline || isNaN(new Date(deadline))) {
            return '';
        }
        console.log(deadline);
        const formattedDate = (new Date(deadline)).toISOString().slice(0,16);

        return formattedDate;
    };
    

    const handleOnChange = (event) => {
        
        const newDate = event.target.value.slice(0,16) + ':00+00:00';
        if (newDate && !isNaN(new Date(newDate))) {
            //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
            handlePostItChange(null, null, null, null, newDate);
        }
        else
        {
            handlePostItChange(null, null, null, null, '0000-00-00T00:00:00+00:00');
        }
    };

    return(
        <div>
            <label className="label" htmlFor="input_deadline">Deadline</label>
            <input onChange={handleOnChange} type="datetime-local" id="input_deadline" name="deadline" className="input" defaultValue={validDateFormat()} />
        </div>
    )
}

export default DeadlineInput;