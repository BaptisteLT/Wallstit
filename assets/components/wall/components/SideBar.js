import { Sidebar, Menu, MenuItem, SubMenu } from 'react-pro-sidebar';
import React, { useState } from "react";

//SideBar documentation: https://www.npmjs.com/package/react-pro-sidebar?activeTab=readme
function SideBar()
{
    const [collapsed, setCollapsed] = useState(false);

    return(
        <Sidebar 
            rootStyles={
                {
                    position: 'absolute', 
                    height: '100%',
                    boxShadow: 'rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;',
                    borderRightStyle: 'unset'
                }
            }
            backgroundColor='rgb(251 251 251 / 94%);'
            collapsed={collapsed}
            collapsedWidth='0px'
            >
            <Menu>
                <SubMenu label="Charts">
                    <MenuItem> Pie charts </MenuItem>
                    <MenuItem> Line charts </MenuItem>
                </SubMenu>
                <MenuItem> Documentation </MenuItem>
                <MenuItem> Calendar </MenuItem>
            </Menu>
        </Sidebar>
    )
}

export default SideBar;
