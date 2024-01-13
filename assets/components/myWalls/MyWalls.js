import React from 'react';
import { Link } from 'react-router-dom';

const MyWalls = () => {
    return(
        <div>
            <h1>This is my MyWalls</h1>
            <Link to="/wall/1">Wall 1</Link>
        </div>
    )
}
export default MyWalls;
