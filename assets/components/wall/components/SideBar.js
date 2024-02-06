import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";
import '../../../styles/Wall/sidebar.css';

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar({addPostIt, postIts})
{
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
                        <SubMenu label={postIt.title ? postIt.title : postIt.content ? postIt.content : 'Empty content'}>
                            <div className="main_wrapper">
                                <div>
                                    <label className="label" for="title">Title</label>
                                    <input className="input" type="text" name="title" id="input_title" defaultValue={postIt.title} />
                                </div>

                                <div>
                                    <label className="label" for="content">Content</label>
                                    <textarea className="input textarea" id="content" name="content" rows="5">
                                        {postIt.content}
                                    </textarea>
                                </div>


                                <div>
                                    <p className="label">Size</p>
                                    <div className="select_size_wrapper">
                                        <div className="select_size">Small</div>
                                        <div className="active select_size">Medium</div>
                                        <div className="select_size">Large</div>
                                    </div>
                                </div>


                                <div>
                                    <p className="label">Color</p>
                                    <div className="select_color_wrapper">
                                        <div className="active select_color select_color_yellow"></div>
                                        <div className="select_color select_color_green"></div>
                                        <div className="select_color select_color_blue"></div>
                                        <div className="select_color select_color_orange"></div>
                                        <div className="select_color select_color_pink"></div>
                                    </div>
                                </div>
                               

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
