import React from "react";
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import '../../styles/reusable/initialDeleteIcon.css';

function InitialDeleteIcon({ handleDelete, className })
{
    return(
        <span onClick={handleDelete} className={className}><DeleteForeverIcon /></span>
    );
}

export default InitialDeleteIcon;