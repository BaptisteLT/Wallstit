import React from "react";
import '../../styles/reusable/container.css';

function Container({children, className = ''})
{
    return(
        <div className={"container " + className}>
            {children}
        </div>
    );
}

export default Container;