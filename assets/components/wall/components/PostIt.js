import React from "react";
import '../../../styles/Wall/post-it.css'
import Draggable, {DraggableCore} from 'react-draggable';

function PostIt({scale}){

    const handleStart = (() => {

    });
    
    const handleDrag = (() => {

    });

    const handleStop = (() => {

    });



    return(
        <Draggable
            handle=".post-it"
            defaultPosition={{x: 0, y: 0}}
            position={null}
            grid={[25, 25]}
            scale={scale}
            onStart={handleStart}
            onDrag={handleDrag}
            onStop={handleStop}>
                <div className="panning-disabled post-it">
                    <div className="post-it-header panning-disabled">

                    </div>

                    <div className="post-it-content panning-disabled">
                        the panning-disabled is required even in the children elements apparently
                    </div>
                </div>
        </Draggable>


    );
}

export default PostIt;