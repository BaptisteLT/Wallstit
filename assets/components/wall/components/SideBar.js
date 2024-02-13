import React, { useEffect } from "react";
import '../../../styles/Wall/SideBar/sidebar.css';
import PostItSubMenuContent from './SideBar/SubMenuContent/PostItSubMenuContent';
import WallSubMenuContent from './SideBar/SubMenuContent/WallSubMenuContent';
import GeneralSubMenuContent from './SideBar/SubMenuContent/GeneralSubMenuContent';
import { usePostItContext } from '../PostItContext';
import Menu from './SideBar/Menu';
import DropDown from './SideBar/DropDown';
import Item from './SideBar/Item';
import AddIcon from '@mui/icons-material/Add';

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar()
{
    //TODO: Optimisation potentielle: Je suis casi sûr que le problème vient de postIt pour le re-render des composants
    const { postIts, addPostIt, activePostItMenuUuid, sideBarSize, wallBackground } = usePostItContext();

    function handleAddPostIt(){
        addPostIt();
    }

    return(
        /*TODO: rajouter des ICONS de post-it, etc*/
        <Menu sideBarSize={sideBarSize}> 
            <DropDown open={activePostItMenuUuid} parentDropDown={true} label="Post-its">
                {postIts.map((postIt) => (
                    //On met le titre s'il existe, autrement si n'existe pas on regarde si le content existe, et si aucun des deux n'existe on affiche "Empty content"
                    <DropDown color={postIt.color} id={postIt.uuid} open={activePostItMenuUuid === postIt.uuid} key={postIt.uuid} label={postIt.title ? postIt.title : postIt.content ? postIt.content : 'Empty content'}>
                        <PostItSubMenuContent deadline={postIt.deadline} size={postIt.size} content={postIt.content} title={postIt.title} color={postIt.color} uuid={postIt.uuid} />
                    </DropDown>
                ))}
            </DropDown>

            <DropDown parentDropDown={true} label="Wall settings (plus un chargement des items)">
                <WallSubMenuContent wallBackground={wallBackground} />
            </DropDown>

            <DropDown parentDropDown={true} label="General preferences">
                <GeneralSubMenuContent sideBarSize={sideBarSize} />
            </DropDown>

            <Item onClick={handleAddPostIt}>
                <span>Create a new Post-it</span>
                <AddIcon fontSize="medium" />
            </Item>
        </Menu>
    )
}

export default SideBar;
