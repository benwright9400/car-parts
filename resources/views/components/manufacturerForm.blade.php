<div style="width: 50%; margin: auto; margin-top: 4rem;">
    <h1>Part</h1>
    <form
        @if (isset($state) && $state === "edit")
             action='../manufacturer/{{ isset($manufacturer) ? $manufacturer['id'] : null}}' method='PUT'
        @elseif((isset($state) && $state === "create"))
             action='{{ route('manufacturer.store') }}' method='POST'
        @endif
        enctype = "multipart/form-data"
        id="submit-form"
        >
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div id="alert-dialog" class="alert alert-danger" style="visibility: hidden" role="alert">
        </div>

        <input id="hidden-value" type="hidden" name="id" value="{{ isset($manufacturer) ? $manufacturer['id'] : null}}" {{isset($state) ? null : "disabled"}}>

        <div class="mb-3">
          <label for="partName" class="form-label">Name</label>
          <input type="email" class="form-control" id="partName" name="name" value="{{ isset($manufacturer) ? $manufacturer['name'] : null }}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="partsOnSale" class="form-label">Parts on sale</label>
            <input type="email" class="form-control" id="sell_parts" name="partsOnSale" value="{{ isset($manufacturer) ? $manufacturer['parts_on_sale'] : null}}" disabled>
        </div>
        <div class="mb-3">
            <label for="sell_parts" class="form-label">On sale</label>
            <select id="sell_parts" name="sell_parts" class="form-select" aria-label="Default select example" {{isset($state) ? null : "disabled"}}>
                <option value="1" {{ isset($manufacturer) && $manufacturer['sell_parts'] == true ? "selected" : null }}>Yes</option>
                <option value="0" {{ (isset($manufacturer) && $manufacturer['sell_parts'] == false) ? "selected" : null }}>No</option>
            </select>
        </div>

        @if (isset($state) && $state === "edit")
            <button type="submit" onclick="post()" class='btn btn-info'>Save</button>
        @elseif((isset($state) && $state === "create"))
            <button type="submit" onclick="post()" class='btn btn-info'>Create</button>
        @else
            <button class='btn btn-info'><a href="../manufacturers/{{ isset($manufacturer) ? $manufacturer['id'] : null}}/edit">Edit</a></button>
        @endif
        
    </form>

    <script>
        function post(e) {
            document.getElementById('submit-form').addEventListener('submit', function(e) {e.preventDefault();});
            // e.preventDefault();
            fetch('http://localhost:8000/manufacturers/' + getId(), {
            method: 'PATCH',
            body: JSON.stringify({
                name: document.getElementById("partName").value,
                sell_parts: document.getElementById("sell_parts").value,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
            })
            .then((response) => response.text())
            .then((json) => {
                console.log(json);
                if(json === "success") {
                    window.open('http://localhost:8000/manufacturers/' + getId(), "_self");
                } else {
                    document.getElementById('alert-dialog').style.visibility = "visible";
                    document.getElementById('alert-dialog').innerHTML = json;
                }
            });
        }

        function getId() {
            return document.getElementById("hidden-value").value;
        }
        
    </script>
</div>

