import React, { useContext, useState } from 'react';
import { useParams } from 'react-router-dom';
import '../../styles/Wall/wall.css';
import Grid from './components/Grid';
import Zoom from './components/Zoom';
import PostIt from './components/PostIt';


function Wall() {
  // Access the 'id' parameter from the route
  //https://github.com/BetterTyped/react-zoom-pan-pinch pour le zoom in and out
  //Pour le grid: https://www.npmjs.com/package/react-gridlines
  //Pour le sidebar menu: https://www.npmjs.com/package/react-pro-sidebar
  //Pour resize la div: https://www.npmjs.com/package/react-resizable
  let { id } = useParams();

  /*TODO: lister les post-its à gauche et pouvoir cliquer dessus grâce à ZoomToElement de la librairie react-zoom-pan-pinch?*/

  const [scale, setScale] = useState(1);

  //On met à jour le scale pour le passer au post-it qui en a besoin
  function updateScale(elements)
  {
    const scale = elements.state.scale;
    setScale(scale);
  }

  return (
      <Zoom handleTransform={updateScale} initialScale={scale}>
        <Grid id={id}>
            <PostIt scale={scale} />
            <h1>Grid Component</h1>
            <p>ID: {id}</p> {/* Display the 'id' parameter value */}
        </Grid>
      </Zoom>
  );
}

export default Wall;