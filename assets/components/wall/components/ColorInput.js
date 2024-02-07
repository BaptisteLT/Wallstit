import React from "react";
import '../../../styles/Wall/colorInput.css';
import '../../../styles/Wall/generalInput.css';

function ColorInput({ color, handlePostItChange })
{
    const handleOnClick = (color) => {
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handlePostItChange(null, null, null, color);
    }

    return(
        <div>
            <p className="label">Color</p>
            <div className="select_color_wrapper">
                <div className="active select_color select_color_yellow" onClick={() => handleOnClick('yellow')}></div>
                <div className="select_color select_color_green" onClick={() => handleOnClick('green')}></div>
                <div className="select_color select_color_blue" onClick={() => handleOnClick('blue')}></div>
                <div className="select_color select_color_orange" onClick={() => handleOnClick('orange')}></div>
                <div className="select_color select_color_pink" onClick={() => handleOnClick('pink')}></div>
            </div>
        </div>
    )
}

export default ColorInput;