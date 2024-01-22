import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

function MyWalls()
{
    const [walls, setWalls] = useState([]);

    //TODO: trait created_at et updated_at

    // Fetch data when the component mounts
    const fetchData = async () => {
        try {
            const response = await axios.get('/api', {
                withCredentials: true,
            });
            // Assuming the response.data is an array of walls
            console.log(response);
            //setWalls(response.data);
        } catch (error) {
            console.log('Error fetching walls:', error);
        }
        console.log('/api call done');
    };
    
    
    useEffect(() => {
        fetchData();
    }, []); // Empty dependency array ensures the effect runs only once


    return(
        <div>
            <h1>This is my MyWalls</h1>
            <Link to="/wall/1">Wall 1</Link>
        </div>
    )
}
export default MyWalls;
