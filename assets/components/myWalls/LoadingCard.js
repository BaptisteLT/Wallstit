import React from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';

function LoadingCard()
{

    return(
        <CardWrapper style={{marginBottom: '18px'}}>
            <Link to='#'>
                <CardContent isLoading={true}></CardContent>
            </Link>
            <p>&nbsp;</p>
        </CardWrapper>
    )
}
export default LoadingCard;
