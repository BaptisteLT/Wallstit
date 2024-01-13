import React from 'react';
import { useParams } from 'react-router-dom';

function Wall() {
  // Access the 'id' parameter from the route
  let { id } = useParams();

  return (
    <div>
      <h1>Wall Component</h1>
      <p>ID: {id}</p> {/* Display the 'id' parameter value */}
    </div>
  );
}

export default Wall;