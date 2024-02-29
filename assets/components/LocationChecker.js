import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';

/**
 * Used when navigating to a different page to see if the footer should be displayed or not
 */
function LocationChecker({ displayFooter })
{
    const location = useLocation();

    useEffect(() => {
        //Si l'URL inclue /wall/, on affiche pas le footer, autrement on l'affiche
        displayFooter(!location.pathname.includes('/wall/'));
    })
}

export default LocationChecker;