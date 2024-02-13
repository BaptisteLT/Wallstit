import React, { useState } from "react";
import SizeInput from "../../Inputs/SizeInput";
import { usePostItContext } from '../../../PostItContext';

function GeneralSubMenuContent({ sideBarSize })
{
    const { updateSideBarSize, setSideBarSize } = usePostItContext();

    const [sideBarSizeTimeoutCallback, setSideBarSizeTimeoutCallback] = useState(null);

    function handleSideBarChange(size)
    {
        setSideBarSize(size);
        // Clear the timeout if it exists
        if (sideBarSizeTimeoutCallback) {
            clearTimeout(sideBarSizeTimeoutCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PUT au serveur
        const newTimeoutCallback = setTimeout(() => {
            updateSideBarSize(size);
        }, 2500);

        // Store the callback in the state
        setSideBarSizeTimeoutCallback(newTimeoutCallback);
    }

    

    return(
        <div className="main_wrapper">
            {/*Font size (14, 16 (default), 18, 20?)*/}
            <SizeInput label='Sidebar Size' handleSideBarChange={handleSideBarChange} size={sideBarSize} />
        </div>
    )
}

export default GeneralSubMenuContent;