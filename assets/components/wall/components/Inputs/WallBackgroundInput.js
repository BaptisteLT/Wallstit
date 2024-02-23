import React from "react";
import '../../../../styles/Wall/Inputs/wallBackgroundInput.css';
import '../../../../styles/Wall/Inputs/generalInput.css';


function WallBackgroundInput({label, wallBackground, handleChange})
{
    function handleOnClick(wallBackground)
    {
        handleChange('background', wallBackground);
    }

    return(
        <>
            <p className="label">{label}</p>
            <div className="select-background-wrapper">
                <div className={"select-background grid " + (wallBackground === null || wallBackground === 'grid' ? 'active' : '')} onClick={() => handleOnClick('grid')}></div>
                <div className={"select-background bricks " + (wallBackground === 'bricks' ? 'active' : '')} onClick={() => handleOnClick('bricks')}></div>
                <div className={"select-background cork-board " + (wallBackground === 'cork-board' ? 'active' : '')} onClick={() => handleOnClick('cork-board')}></div>
                <div className={"select-background flowers-colorful " + (wallBackground === 'flowers-colorful' ? 'active' : '')} onClick={() => handleOnClick('flowers-colorful')}></div>
                <div className={"select-background flowers-dark " + (wallBackground === 'flowers-dark' ? 'active' : '')} onClick={() => handleOnClick('flowers-dark')}></div>
                <div className={"select-background grouted-natural-stone " + (wallBackground === 'grouted-natural-stone' ? 'active' : '')} onClick={() => handleOnClick('grouted-natural-stone')}></div>
                <div className={"select-background multi-coloured-tiles " + (wallBackground === 'multi-coloured-tiles' ? 'active' : '')} onClick={() => handleOnClick('multi-coloured-tiles')}></div>
                <div className={"select-background wood " + (wallBackground === 'wood' ? 'active' : '')} onClick={() => handleOnClick('wood')}></div>
            </div>
        </>
    );
}

export default WallBackgroundInput;