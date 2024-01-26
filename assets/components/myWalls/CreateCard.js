import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import AddIcon from '@mui/icons-material/Add';
import axios from "axios";
import { toast } from 'react-toastify';

import CardWrapper from './CardWrapper';
import CardContent from './CardContent';

function CardCreate()
{
    const navigate = useNavigate();

    function createAndRedirect()
    {
        axios.post('/api/my-wall')
        .then(function(response){
            if(response.status === 200)
            {
                const newWallId = response.data.id;
                //Permet de se rendre sur le nouveau mur crée
                navigate('/wall/' + newWallId);
            }
            else
            {
                throw new Error;
            }
        })
        .catch(function(error){
            toast.error('An error occured while trying to create a new wall.');
        })
    }

    return(
        <CardWrapper description={'Create a new Wall.'}>
            <Link onClick={createAndRedirect} to='#'>
                <CardContent>
                    <AddIcon />
                </CardContent>
            </Link>
        </CardWrapper>
    )
}
export default CardCreate;
