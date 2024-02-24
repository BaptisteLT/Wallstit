import React from 'react';
import '../../styles/MyWalls/cardWrapper.css';
import InitialDeleteIcon from '../reusable/InitialDeleteIcon';

function CardWrapper({handleDelete = null, isLoading = false, description, styling, children})
{
    return(
        <div className="card-wrapper" style={styling}>
            { handleDelete ? <InitialDeleteIcon className="nitialDeleteIcon icon topRight" handleDelete={handleDelete} /> : null }
            <div className={'card '+ (isLoading ? 'loading' : '')}>
                {children}
            </div>
            <p>{description ? description : <>&nbsp;</>}</p>
        </div>
    )
}
export default CardWrapper;
