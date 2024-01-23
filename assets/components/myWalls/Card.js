import React from 'react';
import { Link } from 'react-router-dom';
import CardWrapper from './CardWrapper';
import CardContent from './CardContent';
import '../../styles/MyWalls/card.css';

function Card({href, title, description})
{
    return(
        <CardWrapper>
            <Link to={href}>
                <CardContent>
                    <h2>{title}</h2>
                </CardContent>
            </Link>
            
            <p>{description}</p>
        </CardWrapper>
    )
}
export default Card;
