import React from 'react';
import '../../styles/MyWalls/cardWrapper.css';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';

function CardWrapper({handleDelete = null, isLoading = false, description, styling, children})
{
    return(
        <div className="card-wrapper" style={styling}>
            { handleDelete ? <span onClick={handleDelete} className="initialDeleteIcon icon topRight"><DeleteForeverIcon /></span> : null }
            <div className={'card '+ (isLoading ? 'loading' : '')}>
                {children}
            </div>
            <p>{description ? description : <>&nbsp;</>}</p>
        </div>
    )
}
export default CardWrapper;
