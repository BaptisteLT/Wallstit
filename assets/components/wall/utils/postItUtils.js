import axios from "axios";
import { toast } from 'react-toastify';

/**
 * Récupère les dimensions du post-it en fonction de la taille passée en paramètres
 * 
 * @param {string} sizeString - Size of the post-it ('small', 'large', or default is 'medium').
 * @returns {{ postItDimensions: {width: number, height: number}, innerDimensions: {headerHeight: number, contentHeight: number} }} - Object containing post-it and inner dimensions.
 */
function getDimensionsFromSize(sizeString){

    let dimensions = {};

    switch (sizeString) {
        case 'small':
            dimensions = {
                postItDimensions: {width: 180, height: 170},
                innerDimensions: {headerHeight: 30, contentHeight: 134}
            };
            break;
        case 'large':
            dimensions = {
                postItDimensions: {width: 240, height: 210},
                innerDimensions: {headerHeight: 30, contentHeight: 174}
            };
            break;
        //Medium size by default
        default:
            dimensions = {
                postItDimensions: {width: 210, height: 190},
                innerDimensions: {headerHeight: 30, contentHeight: 154}
            };
    }
    return dimensions;
}

/**
 * Va mettre à jour la position en base de données et affiche les erreurs s'il y en a.
 * 
 * @param {string} uuid 
 * @param {integer} positionX 
 * @param {integer} positionY 
 */
function updatePositionInDB(uuid, positionX, positionY){
    axios.patch('/api/post-it/'+uuid,{
        positionX: positionX,
        positionY: positionY
    })
    .catch(function(error){
        toast.error(error.response.data.error || 'An error occurred');
    });
}


/**
 * Va mettre à jour le title, content, size, color en BDD
 * 
 * @param {*} postIt 
 */
function updatePostItInDB(postIt)
{
    axios.patch('/api/post-it/'+postIt.uuid, {
        title: postIt.title,
        content: postIt.content,
        color: postIt.color,
        size: postIt.size,
        deadline: postIt.deadline
    })
    .catch(function(error){
        console.log(error)
        toast.error(error.response.data.error || 'An error occurred');
    });
}

    

export { getDimensionsFromSize, updatePositionInDB, updatePostItInDB };