import React from 'react';
import ReactDOM from 'react-dom/client';
import Navbar from './Navbar';
import ManufacturerPage from './pages/manufacturer/ManufacturerPage';
import ManufacturersPage from './pages/manufacturer/ManufacturersPage';
import PartPage from './pages/part/PartPage';
import PartsPage from './pages/part/PartsPage';

function getPage() {
    let pathName = window.location.pathname;
    console.log(pathName);

    if(pathName.includes("/manufacturers/")) return <ManufacturerPage />;

    if(pathName === "/manufacturers") return <ManufacturersPage />;

    if(pathName.includes("/parts/")) return <PartPage />;
    
    if(pathName === "/parts") return <PartsPage />;
}

function App() {
    return (
        <div>
            <Navbar />
            {getPage()}
        </div>
    );
}

export default App;

// if (document.getElementById('app')) {
//     const Index = ReactDOM.createRoot(document.getElementById("app"));

//     Index.render(
//         <Example/>
//     )
// }
