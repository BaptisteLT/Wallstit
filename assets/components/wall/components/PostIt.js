import React from "react";
import '../../../styles/Wall/post-it.css'

function PostIt(){
    return(
        <div className="panning-disabled post-it">
            <div className="post-it-header panning-disabled">

            </div>
            <div className="post-it-content panning-disabled">
                the panning-disabled is required even in the children elements apparently
            </div>
        </div>
    );
}

export default PostIt;