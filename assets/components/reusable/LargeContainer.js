import React from "react";
import '../../styles/reusable/largeContainer.css';

function LargeContainer({children, className = ''})
{
    return(
        <div className={"largeContainer " + className}>
            {children}
        </div>
    );
}

export default LargeContainer;