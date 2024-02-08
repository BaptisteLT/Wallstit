import React from "react";
import '../../../styles/Wall/sizeInput.css';
import '../../../styles/Wall/generalInput.css';


function SizeInput({ size, handlePostItChange })
{

    const handleOnClick = (size) =>{
        //Va appeler la méthode mère pour mettre à jour seulement après 2.5 secondes d'inactivité afin d'éviter de spam le serveur d'appels API
        handlePostItChange(null, null, size);
    }
    
    return(
        <div>
            <p className="label">Size</p>
            <div className="select_size_wrapper">
                <div className={"select_size " + (size === 'small' ? 'active' : '')} onClick={() => handleOnClick('small')}>Small</div>
                <div className={"select_size " + (size === 'medium' ? 'active' : '')} onClick={() => handleOnClick('medium')}>Medium</div>
                <div className={"select_size " + (size === 'large' ? 'active' : '')} onClick={() => handleOnClick('large')}>Large</div>
            </div>
        </div>
    )
}

export default SizeInput;