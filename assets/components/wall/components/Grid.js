import React from 'react';
import GridLines from 'react-gridlines';
import '../../../styles/Wall/grid.css';


function Grid({children}) {

  return (
    <GridLines className="grid-area" cellWidth={60} strokeWidth={2} cellWidth2={12}>
        {children}
    </GridLines>
  );
}

export default Grid;