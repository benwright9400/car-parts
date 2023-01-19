import React from 'react';

function Navbar() {
    return <nav className="navbar navbar-expand-lg navbar-light bg-light">
        <div className="container-fluid">
            <ul className="navbar">
                <a className="navbar-brand" href="#">PartLog</a>
                <li className="nav-item">
                    <a className="nav-link active" aria-current="page" href="../parts">Parts</a>
                </li>
                <li className="nav-item">
                    <a className="nav-link active" aria-current="page" href="../manufacturers">Manufacturers</a>
                </li>
            </ul>
        </div>
    </nav>;
};

export default Navbar;