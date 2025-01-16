<form method="POST" action="/pet/delete">
    @csrf
    @method('DELETE')
    <div class="mt-6">
        <label>id</label>
        <input name="id" id="id" > </br>
    </div>

    <div class="flex justify-end mt-p pt-6 border-t border-gray-200">
        <button type="submit">Post</button>
    </div>
</form>
