import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Card from './Card';
import CreateCard from './CreateCard';
import LoadingCards from './LoadingCards';
import '../../styles/MyWalls/mainPage.css';
import { toast } from 'react-toastify';

function MyWalls()
{
    const [walls, setWalls] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    //TODO: trait created_at et updated_at

    function fetchData()
    {
        //TODO: le problème du 401 qui ne passe pas par l'interceptor est que le useEffect de l'interceptor est chargé après la requête
        //setTimeout(() => {
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
        })
        .catch(function(error){
            toast.error('An error occured while trying to retrieve your walls.')
        })
        //  }, 5000);
          

        
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
                <Card id={wall.id} key={wall.id} title={wall.name} description={wall.description} href={"/wall/" + wall.id} />
            ))}
        </div>
    )
}
export default MyWalls;
