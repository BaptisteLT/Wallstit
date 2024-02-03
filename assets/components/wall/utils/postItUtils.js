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

export { getDimensionsFromSize };