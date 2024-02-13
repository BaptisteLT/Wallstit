import React from 'react';
import GridLines from 'react-gridlines';
import '../../../styles/Wall/grid.css';

function Grid({ wallBackground, children }) 
{
  return (
    <>
      {wallBackground === null || wallBackground === 'grid' ?
        <GridLines className="grid-area" cellWidth={60} strokeWidth={2} cellWidth2={12}>
          {children}
        </GridLines>
      :
        <div className={'custom-background ' + wallBackground}>
          {children}
        </div>
      }
    </>
  );
}

export default Grid;