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
                <div id="my-account-avatar-wrapper">
                    <img src="https://lh3.googleusercontent.com/a/ACg8ocJn4wdx_5y_FWDqXQ_WX8-aw_nlZSNO2qC9kdjKCrNpGfY=s96-c" alt="avatar image" />
                </div>

                Edit profile
                <div id="my-account-username-wrapper">
                    <label htmlFor="username">Username</label>
                    <input id="username" name="username" type='text' defaultValue='PabloEscargot' />
                </div>
               
                date de création du compte
            </div>
        </Container>
    )
}
export default MyAccount;
