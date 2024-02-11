import React, { useEffect, useState } from 'react';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import '../../../../styles/Wall/SideBar/dropDown.css';

function DropDown({ open = false, label, parentDropDown = false, children })
{
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    useEffect(() => {
      if(open){
        setIsMenuOpen(true);
      }
      else{
        setIsMenuOpen(false);
      }
    }, [open])

    const toggleMenu = () => {
      setIsMenuOpen(!isMenuOpen);
    };
  
    return(
        <div>
            <div className={'dropDownTitle ' + (isMenuOpen ? 'active' : '') + (parentDropDown ? '' : ' parentDropDown')} onClick={toggleMenu}>
              <span>{ label }</span>
              <span className={isMenuOpen ? 'rotate' : 'rotate-default'}><ArrowForwardIosIcon /></span>
            </div>
    
            {isMenuOpen && (
            <div className={(parentDropDown ? '' : ' parentDropDown')}>
                { children }
            </div>
            )}
        </div>
    );
}

export default DropDown;