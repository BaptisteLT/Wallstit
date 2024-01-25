import React from 'react';
import '../../styles/MyWalls/cardWrapper.css';
import DeleteIcon from '@mui/icons-material/Delete';

function CardWrapper({handleDelete = null, isLoading = false, description, children})
{
    return(
        <div className="card-wrapper">
            { handleDelete ? <span onClick={handleDelete} className="initialDeleteIcon icon topRight"><DeleteIcon /></span> : null }
            <div className={'card '+ (isLoading ? 'loading' : '')}>
                {children}
            </div>
            <p>{description ? description : <>&nbsp;</>}</p>
        </div>
    )
}
export default CardWrapper;
