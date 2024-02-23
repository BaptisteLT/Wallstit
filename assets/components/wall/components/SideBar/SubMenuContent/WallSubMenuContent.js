import React, { useState } from "react";
import WallBackgroundInput from '../../Inputs/WallBackgroundInput';
import { usePostItContext } from '../../../PostItContext';
import TitleInput from "../../Inputs/TitleInput";
import ContentInput from "../../Inputs/ContentInput";


function WallSubMenuContent({ wallBackground, wallName, wallDescription })
{
    const { setWallBackground, setWallName, setWallDescription } = usePostItContext();

    function handleWallChange(key, value)
    {
        if(key === 'title')
        {
            setWallName(value);
        }
        if(key === 'content')
        {
            setWallDescription(value);
        }
        if(key === 'background')
        {
            setWallBackground(value);
        }
    }

    return(
        <div className="main_wrapper">
            <TitleInput label='Title' title={wallName} handleChange={handleWallChange} />
            <ContentInput label='Description' content={wallDescription} handleChange={handleWallChange} />
            <WallBackgroundInput wallBackground={wallBackground} label="Backgrounds" handleChange={handleWallChange} />
        </div>
    )
}

export default WallSubMenuContent;