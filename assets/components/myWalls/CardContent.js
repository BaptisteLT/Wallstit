import React from 'react';

function CardContent({isLoading = false,children})
{
    return(
        <div className={'card ' + (isLoading === true ? 'loading' : '')}>
            {children}
        </div> 
    )
}
export default CardContent;
