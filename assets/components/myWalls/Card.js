import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';
import '../../styles/MyWalls/card.css';
import DeleteIcon from '@mui/icons-material/Delete';
import CloseIcon from '@mui/icons-material/Close';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';

function Card({href, title, description})
{
    const [isDeleteOpen, setIsDeleteOpen] = useState(false);

    function handleDeleteOpen()
    {
        setIsDeleteOpen(!isDeleteOpen);
        console.log(isDeleteOpen);
        //todo: cliquer sur le bouton doit pouvoir ouvrir le menu de suppression
    }

    return(
        <CardWrapper description={description} handleDelete={handleDeleteOpen}>

            <Link to={href}>
                <CardContent>
                    <h2>{title}</h2>
                </CardContent>
            </Link>

            <div className='deleteConfirm' style={{visibility: (isDeleteOpen ? 'visible' :  'hidden')}}>
                <span className="deleteIcon icon"><DeleteForeverIcon /></span>
                <span className="closeIcon icon topRight" onClick={handleDeleteOpen}><CloseIcon /></span>
            </div>


        </CardWrapper>
    )
}
export default Card;
