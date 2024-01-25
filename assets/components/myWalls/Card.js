import React from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';
import '../../styles/MyWalls/card.css';
import DeleteIcon from '@mui/icons-material/Delete';
import CloseIcon from '@mui/icons-material/Close';

function Card({href, title, description})
{
    function handleDelete()
    {
        //todo: cliquer sur le bouton doit pouvoir ouvrir le menu de suppression
    }

    return(
        <CardWrapper description={description} handleDelete={handleDelete}>

            <Link to={href}>
                <CardContent>
                    <h2>{title}</h2>
                </CardContent>
            </Link>

            <div className='deleteConfirm'>
                <span className="deleteIcon"><DeleteIcon /></span>
                <span className="closeIcon topRight"><CloseIcon /></span>
            </div>


        </CardWrapper>
    )
}
export default Card;
