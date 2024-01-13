import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

//Import des components
import Home from './Home';
import Header from './layouts/Header';
import Footer from './layouts/Footer';
import MyWalls from './myWalls/MyWalls';
import Wall from './wall/Wall';
import PostItPriority from './postItPriority/PostItPriority';
import My404 from './My404';

function App()
{
    return(
        <Router>
            <Header />

            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/my-walls" element={<MyWalls />} />
                <Route path="/post-it-priority" element={<PostItPriority />} />
                <Route path="/wall/:id" element={<Wall />} />
                <Route path="*" element={<My404 />} />
            </Routes>

            <Footer />
        </Router>
    )
}

export default App;