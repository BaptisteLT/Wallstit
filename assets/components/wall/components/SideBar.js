import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar({addPostIt})
{
    const [collapsed, setCollapsed] = useState(false);

    function handleAddPostIt(){
        addPostIt(null, null, 'Change me');
    }

    return(
        <Sidebar 
            rootStyles={
                {
                    position: 'absolute', 
                    height: '100%',
                    boxShadow: 'rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px',
                    borderRightStyle: 'unset'
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

                <SubMenu label="Wall settings">
                    <MenuItem> setting 1 </MenuItem>
                    <MenuItem> setting 2 </MenuItem>
                </SubMenu>

                <MenuItem onClick={handleAddPostIt}>Create new post-it (+ ICON)</MenuItem>
            </Menu>
        </Sidebar>
    )
}

export default SideBar;
