import React, { useState } from "react";
import '../../../styles/Wall/SideBar/sidebar.css';
import SubMenuContent from './SubMenuContent';
import { usePostItContext } from '../PostItContext';
import Menu from './SideBar/Menu';
import DropDown from './SideBar/DropDown';
import Item from './SideBar/Item';
import AddIcon from '@mui/icons-material/Add';

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar()
{
    //TODO:Je suis casi sûr que le problème vient de postIt pour le re-render des composants
    const { postIts, addPostIt, activePostItMenuUuid } = usePostItContext();

    function handleAddPostIt(){
        addPostIt();
    }

    return(
        /*
            <SubMenu label="Wall settings (plus un chargement des items)">
                <MenuItem> setting 1 </MenuItem>
                <MenuItem> setting 2 </MenuItem>
            </SubMenu>

            <SubMenu label="General settings">
                <MenuItem> Size bar width (small, mediumn, large) </MenuItem>
                <MenuItem> Font size (14, 16 (default), 18, 20?) </MenuItem>
            </SubMenu>
        */

        <Menu>
            <DropDown open={activePostItMenuUuid} parentDropDown={true} label="Post-its">
                {postIts.map((postIt) => (
                    //On met le titre s'il existe, autrement si n'existe pas on regarde si le content existe, et si aucun des deux n'existe on affiche "Empty content"
                    //TODO: gérer le open
                    
                    <DropDown open={activePostItMenuUuid === postIt.uuid} key={postIt.uuid} label={postIt.title ? postIt.title : postIt.content ? postIt.content : 'Empty content'}>
                        <SubMenuContent deadline={postIt.deadline} size={postIt.size} content={postIt.content} title={postIt.title} color={postIt.color} uuid={postIt.uuid} />
                    </DropDown>
                ))}
            </DropDown>

            <DropDown parentDropDown={true} label="Wall settings (plus un chargement des items)">
                hi
            </DropDown>

            <DropDown parentDropDown={true} label="General settings">
                hi
            </DropDown>

            <Item onClick={handleAddPostIt}>
                <span>Create a new Post-it</span>
                <AddIcon fontSize="medium" />
            </Item>
        </Menu>
    )
}

export default SideBar;
