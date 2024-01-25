import React from 'react';
import '../../styles/MyWalls/cardWrapper.css';
import DeleteIcon from '@mui/icons-material/Delete';

function CardWrapper({isLoading = false, description, children})
{
    return(
        <div className="card-wrapper">
            <span className="initialDeleteIcon topRight"><DeleteIcon /></span>
            <div className={'card '+ (isLoading ? 'loading' : '')}>
                {children}
            </div>
            <p>{description ? description : <>&nbsp;</>}</p>
        </div>
    )
}
export default CardWrapper;
