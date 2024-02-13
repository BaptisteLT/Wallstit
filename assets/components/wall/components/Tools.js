import React from 'react';
import '../../../styles/Wall/tools.css';
import AddIcon from '@mui/icons-material/Add';
import RemoveIcon from '@mui/icons-material/Remove';

function Tools({zoomIn, zoomOut, centerView}) {
  return (
    <div className="tools">
      <button className="tool zoom" onClick={() => zoomIn()}><AddIcon /></button>
      <button className="tool zoom-out" onClick={() => zoomOut()}><RemoveIcon /></button>
      <button className="tool center" onClick={() => centerView()}>Center</button>
    </div>
  );
}

export default Tools;