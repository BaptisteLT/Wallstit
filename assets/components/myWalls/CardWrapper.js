import React from 'react';
import '../../styles/MyWalls/cardWrapper.css';

function CardWrapper({children})
{
    return(
        <div className="card-wrapper">
           {children}
        </div>
    )
}
export default CardWrapper;
