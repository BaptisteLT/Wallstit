import React, { useState } from 'react';
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
    //Défini si le menu pour supprimer la carte est ouverte ou non
    const [isDeleteOpen, setIsDeleteOpen] = useState(false);
    //Défini si la carte est visible ou non (utilisé pour supprimer la carte)
    const [isCardVisible, setIsCardVisible] = useState(true);
    //Permet de bloquer à 1 click les requêtes vers le serveur lorsque la personne clique sur le bouton de suppression rouge.
    const [isDeleteRequestProcessed, setIsDeleteRequestProcessed] = useState(false);


    function handleWallDeletion() 
    {
        //On regarde si une requête est déjà en cours pour éviter que la personne clique plusieurs fois par accident et que la personne reçoive 1 message success et plusieurs messages d'erreur par la suite.
        if(!isDeleteRequestProcessed)
        {
            setIsDeleteRequestProcessed(true);
            
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
            .finally(function(){
                setIsDeleteRequestProcessed(false);
            });
        }
        
    }



    function handleDeleteOpen()
    {
        setIsDeleteOpen(!isDeleteOpen);
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
