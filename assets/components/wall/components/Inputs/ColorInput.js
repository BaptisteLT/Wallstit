import React from "react";
import '../../../../styles/Wall/Inputs/colorInput.css';
import '../../../../styles/Wall/Inputs/generalInput.css';

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
                <div className={"select_color select_color_yellow " + (color === 'yellow' ? 'active' : '')} onClick={() => handleOnClick('yellow')}></div>
                <div className={"select_color select_color_green " + (color === 'green' ? 'active' : '')} onClick={() => handleOnClick('green')}></div>
                <div className={"select_color select_color_blue " + (color === 'blue' ? 'active' : '')} onClick={() => handleOnClick('blue')}></div>
                <div className={"select_color select_color_orange " + (color === 'orange' ? 'active' : '')} onClick={() => handleOnClick('orange')}></div>
                <div className={"select_color select_color_pink " + (color === 'pink' ? 'active' : '')} onClick={() => handleOnClick('pink')}></div>
            </div>
        </div>
    )
}

export default ColorInput;