import React from "react";
import '../../../../../styles/Home/HomeSection1/components/offerText.css';
import VerifiedIcon from '@mui/icons-material/Verified';

function OfferText({ children })
{
    return (
        <div className="offer-text-wrapper">
            <span><VerifiedIcon fontSize="medium" style={{ color: '#196BCA' }} /></span>
            <h3>{ children }</h3>
        </div>
    );
}

export default OfferText