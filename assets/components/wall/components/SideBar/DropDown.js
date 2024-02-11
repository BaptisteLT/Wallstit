import React, { useEffect, useState } from 'react';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import '../../../../styles/Wall/SideBar/dropDown.css';

function DropDown({ open = false, label, parentDropDown = false, id, children })
{
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    //Ouverture du menu
    useEffect(() => {
      if(open){
        setIsMenuOpen(true);
      }
      else{
        setIsMenuOpen(false);
      }
    }, [open]);

    //Dès que le menu est ouvert on scroll vers l'élément
    useEffect(() => {
      const element = document.getElementById('identifier-'+id);
      if(element !== null && isMenuOpen)
      {
        element.scrollIntoView({block: 'start'});
      }
    }, [isMenuOpen]);



    const toggleMenu = () => {
      setIsMenuOpen(!isMenuOpen);
    };
  
    return(
        <div id={id ? 'identifier-'+id : ''}>
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