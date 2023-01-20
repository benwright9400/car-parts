<div style="width: 80%; margin: auto;">

    <div className="container container-fluid">
        <h1>Parts page</h1>
    </div>

    <button class="btn btn-info"><a href="{{ url('/') }}/parts/create">New Part</a></button>

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
                    <td><input type="checkbox" id="{{$part['id']}}" onClick="post(this)" {{$part['on_sale'] === 1 ? "checked" : null}}></input></td>
                    <td>{{$manufacturers[$part['manufacturer_id']]['name'] }}</td>
                    <td>
                        <button class="btn btn-info"><a href="{{ url('/') }}/parts/{{$part['id']}}/edit">Edit</a></button>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</div>

<script>

    function getSendURL(id) {
        console.log("id " + id);
        return origin+'/parts/' + id + "/";
    }

    function post(e) {
        const id = e.id;
        console.log(e.id);
        console.log(e.checked);

        console.log({sell_parts: ((e.checked) ? 1: 0)});

        fetch(getSendURL(id), {
        method: 'PATCH',
        body: JSON.stringify({
            on_sale: 10,
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
        })
        .then((response) => response.text())
        .then((json) => {
            console.log(json);
            if(json === "success") {
                window.open(location.href, "_self");
            } else {
                Alert(json);
            }
        });
    }
    
</script>