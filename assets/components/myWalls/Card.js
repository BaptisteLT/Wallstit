import React, { useState, unmountComponentAtNode } from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';
import '../../styles/MyWalls/card.css';
import CloseIcon from '@mui/icons-material/Close';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import axios from 'axios';
import { toast } from 'react-toastify';

function Card({href, title, description, id})
{
    const [isDeleteOpen, setIsDeleteOpen] = useState(false);
    const [isCardVisible, setIsCardVisible] = useState(true);

    function handleWallDeletion() {
        //TODO: CSRF
        axios.delete('/api/my-wall/delete/'+id)
        .then(function(response){
            if(response.status === 200)
            {
                setIsCardVisible(false);
                toast.success("Wall removed successfully!")
            }
            else
            {
                throw new Error;
            }
        })
        .catch(function(error){
            toast.error('An error occured while trying to delete the wall.');
        })



    }

    function handleDeleteOpen()
    {
        setIsDeleteOpen(!isDeleteOpen);
        console.log(isDeleteOpen);
        //todo: cliquer sur le bouton doit pouvoir ouvrir le menu de suppression
    }

    return(
        <CardWrapper styling={{ display: isCardVisible ? 'block' : 'none' }} description={description} handleDelete={handleDeleteOpen}>

            <Link to={href}>
                <CardContent>
                    <h2>{title}</h2>
                </CardContent>
            </Link>

            <div className='deleteConfirm' style={{visibility: (isDeleteOpen ? 'visible' :  'hidden')}}>
                <span onClick={handleWallDeletion} className="deleteIcon icon"><DeleteForeverIcon /></span>
                <span className="closeIcon icon topRight" onClick={handleDeleteOpen}><CloseIcon /></span>
            </div>


        </CardWrapper>
    )
}
export default Card;
