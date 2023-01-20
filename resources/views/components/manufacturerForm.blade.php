<div style="width: 50%; margin: auto; margin-top: 4rem;">
    <script>

        //checking for changes on the sell_parts select 
        //input to provide a readable value to the server
        //: The server does not identify values of 0 but the DB reqires values of 0
        let changes = 0;
        function onSelectionChange() {
            changes++;
        }

        function getMethod() {
            const method = document.getElementById('hidden-method').value;
            console.log(method);
            return method;
        }

        function getId() {
            const method = document.getElementById('object-id').value;
            console.log(method);
            return method;
        }

        function getSendURL() {
            const method = document.getElementById('hidden-method').value;
            const origin = location.origin;

            if(method === "PATCH") {
                console.log("returning patch route " + getId());
                return origin + '/manufacturers/' + getId();
            }
            if(method === "POST") {
                return origin + '/manufacturers/';
            }
            return method;
        }

        function post(e) {
            document.getElementById('submit-form').addEventListener('submit', function(e) {e.preventDefault();});
            const origin = location.origin;

            fetch(getSendURL(), {
            method: getMethod(),
            body: JSON.stringify({
                name: document.getElementById("partName").value,
                sell_parts: (changes % 2  === 0) ? 0 : 10,
                parts_on_sale: 0
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
            })
            .then((response) => response.text())
            .then((json) => {
                console.log(json);
                if(json === "success") {
                    window.open(origin + '/manufacturers/', "_self");
                } else {
                    document.getElementById('alert-dialog').style.visibility = "visible";
                    document.getElementById('alert-dialog').innerHTML = json;
                }
            });
        }

        function deleteItem() {
            document.getElementById('submit-form').addEventListener('submit', function(e) {e.preventDefault();});
            fetch(location.href, {
            method: 'DELETE',
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
                    document.getElementById('alert-dialog').style.visibility = "visible";
                    document.getElementById('alert-dialog').innerHTML = json;
                }
            });
        }
        
    </script>

    <h1>Manufacturer</h1>
    <form enctype = "multipart/form-data" id="submit-form">

        {{-- hidden utility items --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div id="alert-dialog" class="alert alert-danger" style="visibility: hidden" role="alert">
        </div>

        <input type="hidden" id="object-id" name="id" value="{{ isset($manufacturer['id']) ? $manufacturer['id'] : null}}" >
        <input type="hidden" id="hidden-method" name="id" value="{{ (isset($state) && $state === "edit") ? 'PATCH' : 'POST'}}" >


        {{-- inputs --}}
        <div class="mb-3">
          <label for="partName" class="form-label">Name</label>
          <input type="text" class="form-control" id="partName" name="name" value="{{ isset($manufacturer) ? $manufacturer['name'] : null }}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="partsOnSale" class="form-label">Parts on sale</label>
            <input type="number" class="form-control" id="parts_on_sale" name="partsOnSale" value="{{ isset($manufacturer) ? $manufacturer['parts_on_sale'] : null}}" disabled>
        </div>
        <div class="mb-3">
            <label for="sell_parts" class="form-label">On sale</label>
            <select id="sell_parts" name="sell_parts" class="form-select" aria-label="Default select example" onchange="onSelectionChange()" {{isset($state) ? null : "disabled"}}>
                <option value="1" {{ isset($manufacturer) && $manufacturer['sell_parts'] == true ? "selected" : null }}>Yes</option>
                <option value="0" {{ (isset($manufacturer) && $manufacturer['sell_parts'] == false) ? "selected" : null }}>No</option>
            </select>
        </div>

        
        {{-- action buttons --}}
        @if (isset($state) && $state === "edit")
            <button onclick="post()" class='btn btn-info'>Save</button>
        @elseif((isset($state) && $state === "create"))
            <button onclick="post()" class='btn btn-info'>Create</button>
        @else
            <button class='btn btn-info'><a href="{{ url('/') }}/manufacturers/{{ isset($manufacturer) ? $manufacturer['id'] : null}}/edit">Edit</a></button>
            <button onclick="deleteItem()" class='btn btn-danger'>Delete</button>
        @endif
        
    </form>

</div>

