
import axios from 'axios';

/**
 * Mettre à jour la taille de la sizeBar
 * 
 * @param data
 */
const updateWallInDB = (wallBackground, wallName, wallDescription, id) => {
    axios.patch('/api/wall/'+id, {
        wallBackground: wallBackground,
        wallName: wallName,
        wallDescription: wallDescription
    })
    .catch(function(error){
        toast.error(error.response?.data?.error || 'An error occurred');
    })
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
        toast.error(error.response?.data?.error || 'An error occurred');
    })
}

export { updateWallInDB, updateSideBarSize };
