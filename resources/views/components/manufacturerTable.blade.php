<div  style="width: 80%; margin: auto;">

    <div className="container container-fluid">
        <h1>Manufacturer page</h1>
    </div>

    <button class="btn btn-info"><a href="{{ url('/') }}/manufacturers/create">New Manufacturer</a></button>

    <table class="table" style="margin: auto">

        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Parts on sale</th>
                <th scope="col">Sell parts</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($manufacturers as $key => $manufacturer)
                <tr>
                    <td>{{$manufacturer['name']}}</td>
                    <td>{{$manufacturer['parts_on_sale']}}</td>
                    <td><input type="checkbox" id="{{$manufacturer['id']}}" onClick="post(this)" {{$manufacturer['sell_parts'] === 1 ? "checked" : null}}></input></td>
                    <td>
                        <button class="btn btn-info"><a href="{{ url('/') }}/manufacturers/{{$manufacturer['id']}}">View Page</a></button>
                        <button class="btn btn-info"><a href="{{ url('/') }}/manufacturers/parts/{{$manufacturer['id']}}">View Parts</a></button>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</div>

<script>
    const origin = location.origin;

    function getSendURL(id) {
        console.log("id " + id);
        return origin + '/manufacturers/' + id + "/";
    }

    function post(e) {
        const id = e.id;
        console.log(e.id);
        console.log(e.checked);

        console.log({sell_parts: ((e.checked) ? 1: 0)});

        fetch(getSendURL(id), {
        method: 'PATCH',
        body: JSON.stringify({
            sell_parts: 10,
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
        })
        .then((response) => response.text())
        .then((json) => {
            console.log(json);
            if(json === "success") {
                window.open(origin + '/manufacturers', "_self");
            } else {
                Alert(json);
            }
        });
    }

</script>