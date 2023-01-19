<div style="width: 50%; margin: auto; margin-top: 4rem;">
    <h1>Part</h1>
    <form
        @if (isset($state) && $state === "edit")
             action='../parts/{{ isset($part) ? $part['id'] : null}}' method='PUT'
        @elseif((isset($state) && $state === "create"))
             action='{{ route('parts.store') }}' method='POST'
        @endif
        enctype = "multipart/form-data"
        id="submit-form"
        >
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- @method('POST') --}}


        <div id="alert-dialog" class="alert alert-danger" style="visibility: hidden" role="alert">
        </div>

        <input type="hidden" name="id" value="{{ isset($part) ? $part['id'] : null}}" {{isset($state) ? null : "disabled"}}>

        <div class="mb-3">
          <label for="partName" class="form-label">Name</label>
          <input type="email" class="form-control" id="partName" name="name" value="{{ isset($part) ? $part['name'] : null }}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="SKU" class="form-label">SKU</label>
            <input type="email" class="form-control" id="SKU" name="SKU" value="{{ isset($part) ? $part['SKU'] : null}}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="email" class="form-control" id="description" name="description" value="{{ isset($part) ? $part['description'] : null }}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="email" class="form-control" id="stock" name="stock_count" value="{{ isset($part) ? $part['stock_count'] : null }}" {{isset($state) ? null : "disabled"}}>
        </div>
        <div class="mb-3">
            <label for="onSale" class="form-label">On sale</label>
            <select id="onSale" name="on_sale" class="form-select" aria-label="Default select example" {{isset($state) ? null : "disabled"}}>
                <option value="1" {{ isset($part) && $part['on_sale'] == true ? "selected" : null }}>Yes</option>
                <option value="0" {{ !(isset($part) && $part['on_sale'] == false) ? "selected" : null }}>No</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="manufacturer" class="form-label">Manufacturer</label>
            <select id="manufacturer" name="manufacturer_id" class="form-select" aria-label="Default select example" {{isset($state) ? null : "disabled"}}>
                <option value="">None</option>
                @foreach ($manufacturers as $manufacturer)
                    <option value="{{$manufacturer['id']}}" value="{{ (isset($part) && $manufacturer['id'] === $part['id']) && $part['id']}}" {{(isset($part) && $manufacturer['id'] === $part['id']) ? "selected" : null}}>{{$manufacturer['name']}}</option>
                @endforeach
            </select>
        </div>

        @if (isset($state) && $state === "edit")
            <button type="submit" onclick="post()" class='btn btn-info'>Save</button>
        @elseif((isset($state) && $state === "create"))
            <button type="submit" onclick="post()" class='btn btn-info'>Create</button>
        @else
            <button class='btn btn-info'><a href="../parts/{{ isset($part) ? $part['id'] : null}}/edit">Edit</a></button>
        @endif
        
    </form>

    <script>
        function post(e) {
            document.getElementById('submit-form').addEventListener('submit', function(e) {e.preventDefault();});
            // e.preventDefault();
            fetch('http://localhost:8000/parts/1', {
            method: 'PATCH',
            body: JSON.stringify({
                name: document.getElementById("partName").value,
                SKU: document.getElementById("SKU").value,
                description: document.getElementById("description").value,
                stock_count: document.getElementById("stock").value,
                on_sale: document.getElementById("onSale").value,
                manufacturer_id: document.getElementById("manufacturer").value,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
            })
            .then((response) => response.text())
            .then((json) => {
                console.log(json);
                if(json === "success") {
                    window.open('http://localhost:8000/parts/1', "_self");
                } else {
                    document.getElementById('alert-dialog').style.visibility = "visible";
                    document.getElementById('alert-dialog').innerHTML = json;
                }
            });
        }
        
    </script>
</div>

