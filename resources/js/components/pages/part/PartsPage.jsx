import React, { useEffect, useState } from "react";

function PartsPage() {
    const [parts, setParts] = useState([]);
    const [count, setCount] = useState(0);

    useEffect(() => {
        if(count < 1) {
            getParts();
            setCount(count + 1);
        }
    });

    async function getParts() {
        let response = await fetch("/parts/all");
        response = await response.json();
        console.log(response);
        setParts(response);
    }

    return (
    <div>
        <div className="container container-fluid">
            <h1>Parts page</h1>
        </div>
        <table class="table" style={{margin: "auto", width: "80%"}}>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">SKU</th>
                <th scope="col">Description</th>
                <th scope="col">Stock</th>
                <th scope="col">On sale</th>
                <th scope="col">Manufacturer</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
            <tbody>
                {parts.map((part) => {
                    return <tr key={part.SKU}>
                        <td>{part.name}</td>
                        <td>{part.SKU}</td>
                        <td>{part.description}</td>
                        <td>{part.stock_count}</td>
                        <td>{part.on_sale === 0 ? "No" : "Yes"}</td>
                        <td>{part.manufacturer_id}</td>
                        <td>
                            <button className="btn btn-info"><a href={"../parts/" + part.id}>View Page</a></button>
                        </td>
                    </tr>;
                })}
                
            </tbody>
        </table>
    </div>
    );
}

export default PartsPage;