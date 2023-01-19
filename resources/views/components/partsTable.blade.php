<div>
    <div className="container container-fluid">
        <h1>Parts page</h1>
    </div>
    <button class="btn btn-info"><a href="../parts/">New Part</a></button>
    <table class="table" style="margin: auto">
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
            @foreach ($parts as $part)
                <tr>
                    <td>{{$part['name']}}</td>
                    <td>{{$part['SKU']}}</td>
                    <td>{{$part['description']}}</td>
                    <td>{{$part['stock_count']}}</td>
                    <td>{{$part['on_sale']}}</td>
                    <td>{{$part['manufacturer_id']}}</td>
                    <td>
                        <button class="btn btn-info"><a href="../parts/{{$part['id']}}">View Page</a></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
