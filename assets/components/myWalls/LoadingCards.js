import React from "react";
import LoadingCard from "./LoadingCard"; // Import the LoadingCard component

function LoadingCards() {
  function loadLoadingCard() {
 
    return Array.from(
            { length: 14 },
            (_, i) => (
                <LoadingCard key={i} />
            )
        );
    }

  return <>{loadLoadingCard()}</>;
}

export default LoadingCards;