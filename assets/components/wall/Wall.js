import React from 'react';
import { useParams } from 'react-router-dom';
import '../../styles/Wall/wall.css';
import Grid from './components/Grid';
import Zoom from './components/Zoom';

function Wall() {
  // Access the 'id' parameter from the route
  //https://github.com/BetterTyped/react-zoom-pan-pinch pour le zoom in and out
  //Pour le grid: https://www.npmjs.com/package/react-gridlines
  //Pour le sidebar menu: https://www.npmjs.com/package/react-pro-sidebar
  //Pour resize la div: https://www.npmjs.com/package/react-resizable
  let { id } = useParams();

  return (
      <Zoom>
        <Grid id={id}>
          <h1>Grid Component</h1>
          <p>ID: {id}</p> {/* Display the 'id' parameter value */}
        </Grid>
      </Zoom>
  );
}

export default Wall;