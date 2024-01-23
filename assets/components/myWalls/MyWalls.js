import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Card from './Card';
import CreateCard from './CreateCard';
import '../../styles/MyWalls/mainPage.css';

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
        <div id="main-wrapper">
            <CreateCard />
            <Card index={1} title={"Card title"} description={"Description"} href={"/wall/" + 1} />
            <Card index={2} title={"Card title"}  description={"Description"} href={"/wall/" + 2}/>
            <Card index={3} title={"Card title"} description={"Description"} href={"/wall/" + 3} />
            <Card index={4} title={"Card title"} description={"Description"} href={"/wall/" + 4} />
            <Card index={5} title={"Card title"} description={"Description"} href={"/wall/" + 5} />
            <Card index={6} title={"Card title"} description={"Description"} href={"/wall/" + 6} />
        </div>
    )
}
export default MyWalls;
