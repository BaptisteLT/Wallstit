import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";
import '../../../styles/Wall/sidebar.css';
import SizeInput from './SizeInput';
import ContentInput from './ContentInput';
import ColorInput from './ColorInput';
import TitleInput from './TitleInput';

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar({setPostIts, addPostIt, postIts})
{
    const [collapsed, setCollapsed] = useState(false);

    const [postItDataCallback, setPostItDataCallback] = useState(null);//TODO: 2.5 secondes sans modif on envoie le call API

    const handlePostItChange = (title = null, content = null, size = null, color = null) => {

        setPostIts
        //TODO: setPostIts.filter(..., Il faut mettre à jour le postIt qui se situe dans setPostIts, il me faudra le uuid aussi
        //title)

        // Clear the timeout if it exists
        if (postItDataCallback) {
            clearTimeout(postItDataCallback);
        }

        //Permet d'attendre X secondes avant d'envoyer le PATCH qui contiendra la positionX et positionY.
        //Si l'utilisateur déplace le post-it avant les X secondes, on clear le timeout et on le relance.
        const newTimeoutCallback = setTimeout(() => {
            //Sauvegarder la position en BDD
            //updatePostItInDB(title, content, size, color); TODO: à implémtener
            alert('it does work!');
        }, 2500);

        // Store the callback in the state
        setPostItDataCallback(newTimeoutCallback);
    };

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
                        <SubMenu key={postIt.uuid} label={postIt.title ? postIt.title : postIt.content ? postIt.content : 'Empty content'}>
                            <div className="main_wrapper">
                                <TitleInput title={postIt.title} handlePostItChange={handlePostItChange} />
                                <ContentInput content={postIt.content} handlePostItChange={handlePostItChange} />
                                <SizeInput size={postIt.size} handlePostItChange={handlePostItChange} />
                                <ColorInput color={postIt.color} handlePostItChange={handlePostItChange} />
                                {/* TODO: ça serait mieux de créer des composants et mettre le CSS dans les composants respectifs. */}

    
                            </div>
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
