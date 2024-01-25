import React from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';

function LoadingCard()
{

    return(
        <CardWrapper isLoading={true}></CardWrapper>
    )
}
export default LoadingCard;
