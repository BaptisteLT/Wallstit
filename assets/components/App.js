import React, { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import Emotion from '@emotion/styled';

//Import des components
import Home from './home/Home';
import Header from './layouts/Header/Header';
import Footer from './layouts/Footer';
import MyWalls from './myWalls/MyWalls';
import Wall from './wall/Wall';
import PostItPriority from './postItPriority/PostItPriority';
import My404 from './layouts/My404';
import GoogleCallback from './GoogleCallback';
import UserCheckComponent from './UserCheckComponent';

//Composant axios interceptor pour renouveler les tokens JWT expirés
import JwtInvalidInterceptor from './JwtInvalidInterceptor';

//Import des fonts utilisés partout du le site
import '../fonts/Roboto/Roboto-Regular.ttf';
import '../fonts/Roboto/Roboto-Bold.ttf';
import '../fonts/Roboto/Roboto-Italic.ttf';
import '../fonts/Roboto/Roboto-Black.ttf';
import '../fonts/Roboto/Roboto-Medium.ttf';

//Contexte qui permet à toute l'application de bénéficier de user et setUser
import { AuthContext, useAuth } from './useAuth';


function App()
{
    
    //Au rendu de App, useAuth est utilisé pour fetch l'utilisateur depuis le localStorage. Puis l'utilisateur peut être utilisé dans toute l'application puisqu'on le passe au AuthContext.Provider
    const { user, setUser } = useAuth();

    //TODO: on App load, il faut checker que le jwt en localStorage est encore valide, si il ne l'est plus il faut faire setUser(null)
    return(
        <AuthContext.Provider value={{user, setUser}}>
            {/*JwtInvalidInterceptor va permettre de regénérer le token JWT lorsqu'il est expiré, 
            en utilisant le refresh token en cookie. Axios va simplement catch les erreurs HTTP 401, et dans ce cas les étapes sont les suivantes:
            1) Récupération d'un nouveau token JWT
            2) On retente la même requête 
            3) Si la requête passe alors c'est bon, si elle ne passe pas cela signifie que le refresh token a expiré et que l'utilisateur doit se reconnecter*/}
            <JwtInvalidInterceptor>
                <Router>
                    {/* UserCheckComponent Va vérifier que le refresh token en localStorage n'est pas expiré, afin de déconnecter l'utilisateur dans le cas présent */}
                    <UserCheckComponent>
                        <Header />
                        <Routes>
                            <Route index path="/" element={<Home />} /> 
                            <Route path="/my-walls" element={<MyWalls />} />
                            <Route path="/google-callback" element={<GoogleCallback />} />
                            <Route path="/post-it-priority" element={<PostItPriority />} />
                            <Route path="/wall/:id" element={<Wall />} />
                            <Route path="*" element={<My404 />} />
                        </Routes>
                        <Footer />
                    </UserCheckComponent>
                </Router>
            </JwtInvalidInterceptor>
        </AuthContext.Provider>
    )
}

export default App;