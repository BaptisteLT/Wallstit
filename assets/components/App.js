import React, { useState,useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

//Import des components
import Home from './home/Home';
import Header from './layouts/Header/Header';
import Footer from './layouts/Footer';
import MyWalls from './myWalls/MyWalls';
import Wall from './wall/Wall';
import PostItPriority from './postItPriority/PostItPriority';
import My404 from './layouts/My404';
import OAuthCallback from './OAuthCallback';
import UserCheckComponent from './UserCheckComponent';

//Composant axios interceptor pour renouveler les tokens JWT expirés
import JwtInvalidInterceptor from './AxiosInterceptors/JwtInvalidInterceptor';
import CSRFTokenInterceptor from './AxiosInterceptors/CSRFTokenInterceptor';


//Import des fonts utilisés partout du le site
import '../fonts/Roboto/Roboto-Regular.ttf';
import '../fonts/Roboto/Roboto-Bold.ttf';
import '../fonts/Roboto/Roboto-Italic.ttf';
import '../fonts/Roboto/Roboto-Black.ttf';
import '../fonts/Roboto/Roboto-Medium.ttf';

//Contexte qui permet à toute l'application de bénéficier de user et setUser
import { AuthContext, useAuth } from './useAuth';
import MyAccount from './MyAccount/MyAccount';
import Login from './Login/Login';
import LocationChecker from './LocationChecker';

function App()
{


    //Au rendu de App, useAuth est utilisé pour fetch l'utilisateur depuis le localStorage. Puis l'utilisateur peut être utilisé dans toute l'application puisqu'on le passe au AuthContext.Provider
    const { user, setUser } = useAuth();
    const [ CSRFToken, setCSRFToken ] = useState(null);
    const [footerActive, setFooterActive] = useState(true);



    //TODO: on App load, il faut checker que le jwt en localStorage est encore valide, si il ne l'est plus il faut faire setUser(null)
    return(
        <AuthContext.Provider value={{ CSRFToken, user, setUser }}>
            {/*CSRFTokenInterceptor va intercepter les requêtes GET, et récupérer le token CSRF pour le mettre dans le useState*/}
            <CSRFTokenInterceptor CSRFToken={CSRFToken} setCSRFToken={setCSRFToken}>
                {/*JwtInvalidInterceptor va permettre de regénérer le token JWT lorsqu'il est expiré, 
                en utilisant le refresh token en cookie. Axios va simplement catch les erreurs HTTP 401, et dans ce cas les étapes sont les suivantes:
                1) Récupération d'un nouveau token JWT
                2) On retente la même requête 
                3) Si la requête passe alors c'est bon, si elle ne passe pas cela signifie que le refresh token a expiré et que l'utilisateur doit se reconnecter*/}
                <JwtInvalidInterceptor>
                    <Router>
                        {/* UserCheckComponent Va vérifier que le refresh token en localStorage n'est pas expiré, afin de déconnecter l'utilisateur dans le cas présent */}
                        <UserCheckComponent>
                            <div id="app-content" style={{minHeight: 'calc(100vh - 160px)'}}>
                                <ToastContainer autoClose={2500} style={{marginTop: '60px'}} />
                                <LocationChecker displayFooter={setFooterActive} />
                                <Header />
                                <Routes>
                                    <Route index path="/" element={<Home />} /> 
                                    <Route path="/login" element={<Login />} />
                                    <Route path="/my-account" element={<MyAccount />} />
                                    <Route path="/my-walls" element={<MyWalls />} />
                                    <Route path="/google-callback" element={<OAuthCallback provider="google" />} />
                                    <Route path="/discord-callback" element={<OAuthCallback provider="discord" />} />
                                    <Route path="/post-it-priority" element={<PostItPriority />} />
                                    <Route path="/wall/:id" element={<Wall />} />
                                    <Route path="*" element={<My404 />} />
                                </Routes>
                            </div>
                            
                            { footerActive ? <Footer /> : null }
                        </UserCheckComponent>
                    </Router>
                </JwtInvalidInterceptor>
            </CSRFTokenInterceptor>
        </AuthContext.Provider>
    )
}

export default App;