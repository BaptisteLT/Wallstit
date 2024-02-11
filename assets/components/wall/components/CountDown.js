import React, { useState, useEffect } from "react";

/**
 * Composant responsable de la décrémentation du champ deadline
 */
function CountDown({ deadline })
{
    const [countDown, setCountDown] = useState('');

    let interval = null;

    useEffect(() => {
        if(deadline instanceof Date)
        {
            //On l'appelle avant autrement on devra attendre 1 seconde.
            decrementCountDown(deadline);

            interval = setInterval(() => {
                decrementCountDown(deadline);
            }, 1000);
        }

        return () => {
            //Quand le composant est unmount, on regarde si l'interval est bien défini.
            if(interval)
            {
                clearInterval(interval);
            }
        }
    }, [deadline])


    function decrementCountDown(deadline){
        const now = new Date();
        const timestampTimeLeft = deadline.getTime() - now.getTime();
        
        //Si le temps est écoulé on affiche simplement la date de la deadline
        if(timestampTimeLeft <= 0)
        {
            setCountDown('Due by: ' + deadline.toLocaleDateString("fr"));
            //On clear l'interval car de toute façon l'affichage restera le même.
            clearInterval(interval);
            return;
        }

        const oneSecond = 1000;
        const oneMinute = oneSecond * 60;
        const oneHour = oneMinute * 60;
        const oneDay = oneHour * 24;

        const days = Math.floor(timestampTimeLeft/oneDay);
        const hours = Math.floor((timestampTimeLeft % oneDay) / oneHour);
        const minutes = Math.floor((timestampTimeLeft % oneHour) / oneMinute);
        const seconds = Math.floor((timestampTimeLeft % oneMinute) / oneSecond);

        //Autrement on affiche le temps restant
        setCountDown(days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's left.')
    }


    return(
        <span className="panning-disabled">{countDown}</span>
    )
}

export default CountDown;