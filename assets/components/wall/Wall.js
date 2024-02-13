import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import '../../styles/Wall/wall.css';
import Grid from './components/Grid';
import Zoom from './components/Zoom';
import PostIt from './components/PostIt';
import { toast } from 'react-toastify';
import axios from 'axios';
import { PostItContext } from './PostItContext';


function Wall() {
  // Access the 'id' parameter from the route
  //https://github.com/BetterTyped/react-zoom-pan-pinch pour le zoom in and out
  //Pour le grid: https://www.npmjs.com/package/react-gridlines
  //Pour resize la div: https://www.npmjs.com/package/react-resizable
  let { id } = useParams();

  const [postIts, setPostIts] = useState([]);
  
  //Défini le menu qui sera ouvert
  const [activePostItMenuUuid, setActivePostItMenuUuid] = useState('');

  const [sideBarSize, setSideBarSize] = useState(null);
  const [wallBackground, setWallBackground] = useState(null);

  const [scale, setScale] = useState(1);
  //TODO: Peut-être le récupérer de l'entité walls?
  const [pageDimensions] = useState({width: 3840, height: 2160});

  //On met à jour le scale pour le passer au post-it qui en a besoin
  function updateScale(elements)
  {
    const scale = elements.state.scale;
    setScale(scale);
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
          content: null,
          color: 'yellow',
          size: 'medium'
        }
      ]);
    })
    .catch(function (error) {
      // handle error
      toast.error('An error occured while creating the new post-it.');
    })
  }

  function retrieveWallPostIts() {
    axios.get('/api/wall/' + id + '/post-its')
    .then(function (response) {
      const data = JSON.parse(response.data);

      setWallBackground(data.background);
      setSideBarSize(data.user.sideBarSize);

      const newPostIts = data.postIts;

      // Merging current post-its with new Post-its
      setPostIts((prevPostIts) => [
        ...prevPostIts,
        ...newPostIts
      ]);
    })
    .catch(function (error) {
      console.log(error);
      // handle error
      toast.error('An error occurred while trying to retrieve the post-its.');
    });
  }
  
  useEffect(() => {
    retrieveWallPostIts();
  }, []);


  /**
   * @param {object} newPostItData 
   * @param {string} uuid 
   * 
   * Met à jour les données d'un seul postIts dans le useState postIts qui contient un array d'objects postIts
   */
  const updatePostIt = (newPostItData, uuid) => {
    let currentPostIt = null;

    setPostIts(prevPostIts => 
      prevPostIts.map((postIt) => {
        if (postIt.uuid === uuid) {
          console.log(postIt);
          const newPostIt = { ...postIt, ...newPostItData };
          currentPostIt = newPostIt;
          console.log(currentPostIt);
          return newPostIt;
        }
        return postIt;
      })
    )

    return currentPostIt;
  };

  /**
   * Ouvre le menu du PostIt (son uuid) passé en paramètres
   * 
   * @param {string} uuid 
   */
  const openPostItMenu = (uuid) => {
    //Permet de refermer si le menu est déjà ouvert, ou d'ouvrir le menu associé à l'uuid du PostIt
    setActivePostItMenuUuid(uuid === activePostItMenuUuid ? null : uuid);
  }

  /**
   * Mettre à jour la taille de la sizeBar
   * 
   * @param {string} sideBarSize 
   */
  const updateSideBarSize = (sideBarSize) => {
    axios.put('/api/general/side-bar-size', {
      sideBarSize: sideBarSize
    })
    .catch(function(error){
      toast.error(error.response.data.error || 'An error occurred');
    })
  }


  /**
   * Mettre à jour la taille de la sizeBar
   * 
   * @param {string} wallBackground 
   */
  const updateWallBackground = (wallBackground) => {
    axios.put('/api/wall/'+id+'/wall-background', {
      wallBackground: wallBackground
    })
    .catch(function(error){
      toast.error(error.response.data.error || 'An error occurred');
    })
  }


  return (
    //TODO: voir comment on peut get rid of postIts={postIts}
    <PostItContext.Provider value={{ updatePostIt, addPostIt, openPostItMenu, postIts, activePostItMenuUuid, sideBarSize, updateSideBarSize, wallBackground, updateWallBackground, setSideBarSize, setWallBackground }}>
      <Zoom handleTransform={updateScale} initialScale={scale} pageDimensions={pageDimensions}>
        <Grid wallBackground={wallBackground} id={id}>
          {postIts.map((postIt) => (
            <PostIt 
              key={postIt.uuid}
              scale={scale}
              pageDimensions={pageDimensions}
              title={postIt.title}
              color={postIt.color}
              content={postIt.content}
              deadline={postIt.deadline}
              positionX={postIt.positionX}
              positionY={postIt.positionY}
              size={postIt.size}
              uuid={postIt.uuid}
            />
          ))}
        </Grid>
      </Zoom>
    </PostItContext.Provider>
  );
}

export default Wall;