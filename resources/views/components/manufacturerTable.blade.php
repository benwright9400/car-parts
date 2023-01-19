<div>
    <div className="container container-fluid">
        <h1>Parts page</h1>
    </div>
    <button class="btn btn-info"><a href="../parts/">New Part</a></button>
    <table class="table" style="margin: auto">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Parts on sale</th>
            <th scope="col">On sale</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
        <tbody>
            @foreach ($manufacturers as $manufacturer)
                <tr>
                    <td>{{$manufacturer['name']}}</td>
                    <td>{{$manufacturer['parts_on_sale']}}</td>
                    <td>{{$manufacturer['sell_parts']}}</td>
                    <td>
                        <button class="btn btn-info"><a href="../manufacturers/{{$manufacturer['id']}}">View Page</a></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
