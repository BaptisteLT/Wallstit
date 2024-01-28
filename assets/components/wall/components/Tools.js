import React from 'react';
import { TransformWrapper, TransformComponent } from "react-zoom-pan-pinch";
import '../../../styles/Wall/tools.css';

function Tools({zoomIn, zoomOut, centerView}) {
  return (
    <div className="tools">
        <button onClick={() => zoomIn()}>+</button>
        <button onClick={() => zoomOut()}>-</button>
        <button onClick={() => centerView()}>x</button>
    </div>
  );
}

export default Tools;