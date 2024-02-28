import React, { useEffect, useLayoutEffect, useState } from 'react';
import LargeContainer from '../reusable/LargeContainer';
import '../../styles/MyAccount/myAccount.css';
import axios from 'axios';
import { toast } from 'react-toastify';

import ContentLoader from 'react-content-loader'

const MyAccount = () => {

    const [createdAt, setCreatedAt] = useState(null);
    const [pictureUrl, setPictureUrl] = useState(null);
    const [username, setUsername] = useState(null);
    

    //Changing the body background-color before the page is loaded
    useLayoutEffect(() => {
        document.body.style.backgroundColor = "rgb(251, 251, 251)";
        //Remove background-color when unmounted
        return () => {
            document.body.style.removeProperty('background-color');
        }
    });

    /**
     * Update the user data when clicking on "Save"
     */
    function updateUserData(){
        
        axios.patch('/api/user/me',{
            username: username
        })
        .then(function(response){
            toast.success('Your username has been updated.');
        })
        .catch(function(error){
            toast.error(error.response?.data?.error || 'An error occurred');
        });
    }

    /* Fetch the user data when the page is loaded */
    function fetchUserData()
    {
        axios.get('/api/get-user-info')
        .then(function(response){
            const data = JSON.parse(response.data.user);
            setUsername(data.name);
            setPictureUrl(data.picture);
            setCreatedAt(new Date(data.createdAt));
        })
        .catch(function(error){
            toast.error(error.response?.data?.error || 'An error occurred');
        })
    }
    
    useEffect(() => {
        fetchUserData();
    }, []); // Empty dependency array ensures the effect runs only once


    return(
        
        <LargeContainer className="my-account-container">

            <div id="my-account-wrapper">
                {username ? //On charge le contenu seulement quand les données de l'utilisateur ont été fetch
                    <>
                        <img src={pictureUrl} alt="avatar image" />

                        <span className="header-title">Edit profile</span>

                        <label htmlFor="username">How should we call you?</label>
                        <input className="username-input" id="username" name="username" type="text" onChange={(e) => { setUsername(e.target.value) }} defaultValue={username} />
                    
                        <span className="created-at">Your account was created on {createdAt.toUTCString().slice(0, -13)}</span>

                        <button onClick={updateUserData} className="save-btn">Save</button>

                        <hr className="separator" />

                        <button className="delete-account-btn">Delete Account</button>
                        <span className="delete-account-text">This action is permanent!</span>
                    </>
                : 
                <ContentLoader 
                    width={360}
                    height={540}
                >
                    <circle cx="180" cy="90" r="55" /> 
                    <rect x="80" y="160" rx="6" ry="6" width="200" height="30" /> 
                    <rect x="80" y="210" rx="6" ry="6" width="200" height="60" /> 
                    <rect x="80" y="290" rx="6" ry="6" width="200" height="40" /> 
                    
                    <rect x="80" y="350" rx="6" ry="6" width="200" height="30" /> 
                    
                    <rect x="80" y="460" rx="6" ry="6" width="200" height="30" /> 
                    <rect x="80" y="500" rx="6" ry="6" width="200" height="16" /> 
                </ContentLoader>
                }
                

                
            </div>
        </LargeContainer>
    )
}
export default MyAccount;
