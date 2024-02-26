import React, { useLayoutEffect } from 'react';
import Container from '../reusable/Container';
import '../../styles/MyAccount/myAccount.css';

const MyAccount = () => {

    //Changing the body background-color before the page is loaded
    useLayoutEffect(() => {
        document.body.style.backgroundColor = "rgb(251, 251, 251)";
        //Remove background-color when unmounted
        return () => {
            document.body.style.removeProperty('background-color');
        }
    });

    return(
        <Container className="my-account-container">
            <div id="my-account-wrapper">
                
                <img src="https://lh3.googleusercontent.com/a/ACg8ocJn4wdx_5y_FWDqXQ_WX8-aw_nlZSNO2qC9kdjKCrNpGfY=s96-c" alt="avatar image" />

                

                
                <span className="header-title">Edit profile</span>

                
                <label htmlFor="username">How should we call you?</label>
                <input className="username-input" id="username" name="username" type="text" defaultValue="PabloEscargot" />
               
                <span className="created-at">Your account was created on 10th february 2019</span>

                <button className="save-btn">Save</button>

                <hr className="separator" />

                <button className="delete-account-btn">Delete Account</button>
                <span className="delete-account-text">This action is permanent!</span>

            </div>
        </Container>
    )
}
export default MyAccount;
