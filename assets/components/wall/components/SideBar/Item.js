import React from 'react';
import '../../../../styles/Wall/SideBar/item.css';

function Item({ children, onClick })
{
    return(
        <div className='item' onClick={onClick}>{ children }</div>
    );
}

export default Item;