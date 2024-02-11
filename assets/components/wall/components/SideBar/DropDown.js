import React, { useEffect, useState } from 'react';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import '../../../../styles/Wall/SideBar/dropDown.css';

function DropDown({ open = false, label, parentDropDown = false, id, children })
{
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isMenuManuallyOpen, setIsMenuManuallyOpen] = useState(false);

    //Ouverture du menu depuis l'icone settings
    useEffect(() => {
      if(open){
        setIsMenuOpen(true);
      }
      else{
        setIsMenuOpen(false);
      }
    }, [open]);

    // Effect for scrolling when the menu is open (form the setting icon)
    useEffect(() => {
      if (isMenuOpen && id) {
        const element = document.getElementById('identifier-' + id);
        if (element !== null) {
          element.scrollIntoView({ block: 'start', behavior: 'smooth' });
        }
      }
    }, [isMenuOpen, id]);

    //Ouverture/fermeture manuelle
    const toggleMenu = () => {
      if(isMenuOpen === true && isMenuManuallyOpen === false){
        setIsMenuOpen(false);
      }
      else{
        setIsMenuManuallyOpen(!isMenuManuallyOpen);
      }
    };
  
    return(
        <div id={id ? 'identifier-'+id : null}>
            <div className={'dropDownTitle ' + ((isMenuOpen || isMenuManuallyOpen) ? 'active' : '') + (parentDropDown ? '' : ' parentDropDown')} onClick={toggleMenu}>
              <span>{ label }</span>
              <span className={(isMenuOpen || isMenuManuallyOpen) ? 'rotate' : 'rotate-default'}><ArrowForwardIosIcon /></span>
            </div>
    
            {(isMenuOpen || isMenuManuallyOpen) && (
            <div className={(parentDropDown ? '' : ' parentDropDown')}>
                { children }
            </div>
            )}
        </div>
    );
}

export default DropDown;