import React from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';
import AddIcon from '@mui/icons-material/Add';

function CardCreate()
{
    return(
        <CardWrapper style={{marginBottom: '18px'}}>
            <Link to='#'>
                <CardContent>
                    <AddIcon />
                </CardContent>
            </Link>
            <p>Create a new Wall.</p>
        </CardWrapper>
    )
}
export default CardCreate;
