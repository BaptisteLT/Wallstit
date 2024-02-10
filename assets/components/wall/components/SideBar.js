import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";
import '../../../styles/Wall/sidebar.css';
import SubMenuContent from './SubMenuContent';
import { usePostItContext } from '../PostItContext';

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar()
{
    //TODO: voir si on peut pas utiliser un contexte pour passer setPostIts
    //TODO:Je suis casi sûr que le problème vient de postIt
    const { postIts, addPostIt } = usePostItContext();

    const [collapsed, setCollapsed] = useState(false);

    function handleAddPostIt(){
        addPostIt();
    }

    return(
        <Sidebar 
            rootStyles={
                {
                    position: 'absolute', 
                    height: '100%',
                    borderRightWidth: '2px',
                    borderColor: '#E6E6E6'
                }
            }
            backgroundColor='rgb(251 251 251 / 94%)'
            collapsed={collapsed}
            collapsedWidth='0px'
            >
            <Menu>
                <SubMenu label="Post-its">
                    {postIts.map((postIt) => (
                        //On met le titre s'il existe, autrement si n'existe pas on regarde si le content existe, et si aucun des deux n'existe on affiche "Empty content"
                        //TODO: gérer le open
                        <SubMenu key={postIt.uuid} label={postIt.title ? postIt.title : postIt.content ? postIt.content : 'Empty content'}>
                            <SubMenuContent deadline={postIt.deadline} size={postIt.size} content={postIt.content} title={postIt.title} color={postIt.color} uuid={postIt.uuid} />
                        </SubMenu>
                    ))}
  
                </SubMenu>

                <SubMenu label="Wall settings (plus un chargement des items)">
                    <MenuItem> setting 1 </MenuItem>
                    <MenuItem> setting 2 </MenuItem>
                </SubMenu>

                <SubMenu label="General settings">
                    <MenuItem> Size bar width (small, mediumn, large) </MenuItem>
                    <MenuItem> Font size (14, 16 (default), 18, 20?) </MenuItem>
                </SubMenu>

                <MenuItem onClick={handleAddPostIt}>Create new post-it (+ ICON)</MenuItem>
            </Menu>
        </Sidebar>
    )
}

export default SideBar;
