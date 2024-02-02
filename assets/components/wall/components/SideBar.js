import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar({addPostIt})
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
                    <MenuItem> DESC </MenuItem>
                    <MenuItem> DESC </MenuItem>
                </SubMenu>

                <SubMenu label="Wall settings (plus un chargement des items)">
                    <MenuItem> setting 1 </MenuItem>
                    <MenuItem> setting 2 </MenuItem>
                </SubMenu>

                <MenuItem onClick={handleAddPostIt}>Create new post-it (+ ICON)</MenuItem>
            </Menu>
        </Sidebar>
    )
}

export default SideBar;
