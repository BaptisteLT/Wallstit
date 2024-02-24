import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import '../../styles/Wall/wall.css';
import Grid from './components/Grid';
import Zoom from './components/Zoom';
import PostIt from './components/PostIt';
import { toast } from 'react-toastify';
import axios from 'axios';
import { PostItContext } from './PostItContext';
import { updateWallInDB, updateSideBarSize } from './utils/wallUtils';

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
  const [wallName, setWallName] = useState(null);
  const [wallDescription, setWallDescription] = useState(null);

  //Stocke la fonction de mise à jour des data du Wall dans un setTimeout afin de ne mettre à jour que s'il n'y a eu aucune interaction pendant 2.5 secondes
  const [wallTimeoutCallback, setWallTimeoutCallback] = useState(null);


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

  /**
   * Va charger les post-its sur la page
   */
  function retrieveWallPostIts() {
    axios.get('/api/wall/' + id + '/post-its')
    .then(function (response) {
      const data = JSON.parse(response.data.data);

      setWallBackground(data.background);
      setWallName(data.name);
      setWallDescription(data.description);
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
          const newPostIt = { ...postIt, ...newPostItData };
          currentPostIt = newPostIt;
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

  //Mise à jour en base de données dès que l'une des valeurs wallBackground, wallName, wallDescription est modifiée (avec un délai de 2.5 secondes)
  useEffect(() => {
    // Clear the timeout if it exists
    if (wallTimeoutCallback) {
      clearTimeout(wallTimeoutCallback);
    }

    //Permet d'attendre X milisecondes avant d'envoyer le PATCH au serveur
    const newTimeoutCallback = setTimeout(() => {
      updateWallInDB(wallBackground, wallName, wallDescription, id);
    }, 2500);

    // Store the callback in the state
    setWallTimeoutCallback(newTimeoutCallback);
  }, [wallBackground, wallName, wallDescription])
        

  return (
    //TODO: voir comment on peut get rid of postIts={postIts}
    <PostItContext.Provider value={{ updatePostIt, addPostIt, openPostItMenu, postIts, activePostItMenuUuid, sideBarSize, updateSideBarSize, wallBackground, wallDescription, wallName, setSideBarSize, setWallBackground, setWallDescription, setWallName, setActivePostItMenuUuid }}>
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
              deadlineDone={postIt.deadlineDone}
            />
          ))}
        </Grid>
      </Zoom>
    </PostItContext.Provider>
  );
}

export default Wall;