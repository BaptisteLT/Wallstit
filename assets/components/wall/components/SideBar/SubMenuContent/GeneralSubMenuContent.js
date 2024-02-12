import React from "react";
import SizeInput from "../../Inputs/SizeInput";
import { usePostItContext } from '../../../PostItContext';

function GeneralSubMenuContent({ sideBarSize })
{
    const { updateSideBarSize } = usePostItContext();


    function handleSideBarChange(size)
    {
        updateSideBarSize(size);
    }

    return(
        <div className="main_wrapper">
            <SizeInput label='Sidebar Size' handleSideBarChange={handleSideBarChange} size={sideBarSize} />
        </div>
    )
}

export default GeneralSubMenuContent;