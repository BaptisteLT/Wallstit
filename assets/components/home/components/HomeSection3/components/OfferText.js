import React from "react";
import '../../../../../styles/Home/HomeSection1/components/offerText.css';

function OfferText({ children })
{
    return (
        <div className="offer-text-wrapper">
            <span>✅</span>
            <h3>{ children }</h3>
        </div>
    );
}

export default OfferText