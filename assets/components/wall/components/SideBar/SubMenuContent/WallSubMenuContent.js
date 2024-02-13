import React, { useState } from "react";
import WallBackgroundInput from '../../Inputs/WallBackgroundInput';
import { usePostItContext } from '../../../PostItContext';


function WallSubMenuContent({ wallBackground })
{
    const { updateWallBackground, setWallBackground } = usePostItContext();

    const [wallBackgroundTimeoutCallback, setWallBackgroundTimeoutCallback] = useState(null);

    function handleWallBackgroundChange(wallBackground)
    {
        setWallBackground(wallBackground);
        // Clear the timeout if it exists
        if (wallBackgroundTimeoutCallback) {
            clearTimeout(wallBackgroundTimeoutCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PUT au serveur
        const newTimeoutCallback = setTimeout(() => {
            updateWallBackground(wallBackground);
        }, 2500);

        // Store the callback in the state
        setWallBackgroundTimeoutCallback(newTimeoutCallback);
    }


    return(
        <div className="main_wrapper">
            <WallBackgroundInput wallBackground={wallBackground} label="Backgrounds" handleWallBackgroundChange={handleWallBackgroundChange} />
        </div>
    )
}

export default WallSubMenuContent;