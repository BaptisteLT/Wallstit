import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Card from './Card';
import CreateCard from './CreateCard';
import LoadingCards from './LoadingCards';
import PlaceholderLoading from 'react-placeholder-loading'
import '../../styles/MyWalls/mainPage.css';

function MyWalls()
{
    const [walls, setWalls] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    //TODO: trait created_at et updated_at

    function fetchData()
    {
        // Fetch data when the component mounts
        axios.get('/api/my-walls')
        .then(function(response){
            if(response.status === 200)
            {
                const walls = JSON.parse(response.data.walls);
                setWalls(walls);
                setIsLoading(false);
            }
            else
            {
                throw new Error;
            }
            console.log(response);
        })
        .catch(function(error){
            alert('error while trying to create a new wall');
        })
    }

    
    
    useEffect(() => {
        fetchData();
    }, []); // Empty dependency array ensures the effect runs only once


    return(
        <div id="main-wrapper">
            <CreateCard />

            {/*Conditional rendering*/}
            {isLoading && <LoadingCards />}

            {walls.map((wall) => (
                <Card key={wall.id} title={wall.name} description={wall.description} href={"/wall/" + wall.id} />
            ))}
        </div>
    )
}
export default MyWalls;
