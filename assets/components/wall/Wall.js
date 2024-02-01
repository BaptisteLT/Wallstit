import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import '../../styles/Wall/wall.css';
import Grid from './components/Grid';
import Zoom from './components/Zoom';
import PostIt from './components/PostIt';
import { toast } from 'react-toastify';
import axios from 'axios';

function Wall() {
  // Access the 'id' parameter from the route
  //https://github.com/BetterTyped/react-zoom-pan-pinch pour le zoom in and out
  //Pour le grid: https://www.npmjs.com/package/react-gridlines
  //Pour le sidebar menu: https://www.npmjs.com/package/react-pro-sidebar
  //Pour resize la div: https://www.npmjs.com/package/react-resizable
  let { id } = useParams();

  /*TODO: lister les post-its à gauche et pouvoir cliquer dessus grâce à ZoomToElement de la librairie react-zoom-pan-pinch?*/
  const [postIts, setPostIts] = useState([]);
  const [scale, setScale] = useState(1);
  //TODO: Peut-être le récupérer de l'entité walls?
  const pageDimensions = {width: 3840, height: 2160}

  //On met à jour le scale pour le passer au post-it qui en a besoin
  function updateScale(elements)
  {
    const scale = elements.state.scale;
    setScale(scale);
  }

  useEffect(() => {
    retrieveWallPostIts();
  }, [])

  function retrieveWallPostIts()
  {
    axios.get('/api/wall/'+id+'/post-its')
    .then(function (response) {
      console.log(response); //TODO: setPostIts: ...postIts, nouveau post-it
    })
    .catch(function (error) {
      console.log(error)
      // handle error
      toast.error('An error occured while trying to retrieve the post-its.');
    })
  }










  function addPostIt(positionX, positionY)
  {
    axios.post('/api/post-it', {
      wallId: id,
    })
    .then(function (response) {
      setPostIts([
        ...postIts,
        {
          uuid: response.data.uuid,
          positionX: positionX,
          positionY: positionY,
          content: null
        }
      ]);
    })
    .catch(function (error) {
      // handle error
      toast.error('An error occured while creating the new post-it.');
    })


  }

  return (
      <Zoom handleAddPostIt={addPostIt} handleTransform={updateScale} initialScale={scale} pageDimensions={pageDimensions}>
        <Grid id={id}>
            {postIts.map((postIt) => (
              <PostIt scale={scale} pageDimensions={pageDimensions} postItData={postIt} key={postIt.uuid} />
            ))}
            <h1>Grid Component</h1>
            <p>ID: {id}</p> {/* Display the 'id' parameter value */}
        </Grid>
      </Zoom>
  );
}

export default Wall;