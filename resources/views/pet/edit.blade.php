<form method="POST" action="/pet/edit">
    @csrf
    @method('PUT')
    <div class="mt-6">
        <label>id</label>
        <input name="id" id="id" > </br>
        <label>name</label>
        <input name="name" id="name" > </br>
        <label>categories</label>
        <select name="category" id="category">
            @foreach($categories as $element)
                <option value="{{ $element['name'] }}">{{ $element['name'] }}</option>
            @endforeach
        </select></br>
        <label>tags</label>
        <select name="tag[]" id="tag" multiple>
            @foreach($tags as $element)
                <option value="{{ $element['name'] }}">{{ $element['name'] }}</option>
            @endforeach
        </select></br>
        <label>urls</label>
        <input type="text" id="urls" name="urls"></br>
        <label>status</label>
        <select name="status" id="status">
            @foreach($statuses as $element)
                <option value="{{ $element }}">{{ $element }}</option>
            @endforeach
        </select></br>
    </div>

    <div class="flex justify-end mt-p pt-6 border-t border-gray-200">
        <button type="submit">Post</button>
    </div>

</form>
