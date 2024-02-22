import React from "react";
import CloseIcon from '@mui/icons-material/Close';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import '../../styles/reusable/deleteConfirm.css';

function DeleteConfirm({ handleDeleteMenuOpen, handleItemDelete, menuOpen })
{
    return(
        <div className='deleteConfirm' style={{visibility: (menuOpen ? 'visible' :  'hidden')}}>
            <span onClick={handleItemDelete} className="deleteIcon icon"><DeleteForeverIcon /></span>
            <span className="closeIcon icon topRight" onClick={handleDeleteMenuOpen}><CloseIcon /></span>
        </div>
    );
}

export default DeleteConfirm;