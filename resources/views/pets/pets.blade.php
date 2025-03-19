
<!-- Wyświetlenie statusu operacji -->

<h2>Status of operation: </h2>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

@if(session('delId'))
    <p>ID: {{ session('delId') }}</p>
@endif

@if(session('petData'))
    <h2>Pet Data:</h2>
    <ul>
        <li><strong>ID:</strong> {{ session('petData')['id'] ?? 'not set' }}</li>
        <li><strong>Name:</strong> {{ session('petData')['name'] ?? 'not set' }}</li>
        <li><strong>Status:</strong> {{ session('petData')['status'] ?? 'not set' }}</li>
        <li><strong>Category:</strong>
            <ul>
                <li><strong>ID:</strong> {{ session('petData')['category']['id'] ?? 'not set' }}</li>
                <li><strong>Name:</strong> {{ session('petData')['category']['name'] ?? 'not set' }}</li>
            </ul>
        </li>
        <li><strong>Tags:</strong>
            <ul>
                @foreach(session('petData')['tags'] ?? [] as $tag)
                    <li>
                        <strong>ID:</strong> {{ $tag['id'] ?? 'not set' }},
                        <strong>Name:</strong> {{ $tag['name'] ?? 'not set' }}
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
@endif

@if(session('beforeEdit'))
    <h3>Pet before edit:</h3>
    <pre>{{ json_encode(session('beforeEdit'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
@endif

@if(session('afterEdit'))
    <h3>Pet after edit:</h3>
    <pre>{{ json_encode(session('afterEdit'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
@endif

<!-- Formularz dodania Peta, routowany do kontrolera PetController oraz funkcji addPet() -->

<h1>Add</h1>
<form action="{{ route('pets.addPet') }}" method="post">
    @csrf
    <label for="addId">ID:</label>
    <input type="number" name="addId" id="addId">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">
    <label for="status">Status:</label>
    <input type="text" name="status" id="status">
    <br> <h3>Category: </h3>
    <label for="categoryId">Category ID:</label>
    <input type="number" name="categoryId" id="categoryId">
    <label for="categoryName">Category Name:</label>
    <input type="text" name="categoryName" id="categoryName">
    <br> <h3>Tag: </h3>
    <label for="tagId">Tag ID:</label>
    <input type="number" name="tagId" id="tagId">
    <label for="tagName">Tag Name:</label>
    <input type="text" name="tagName" id="tagName">
    <button type="submit">Add pet</button>
</form>

<!-- Formularz znalezienia Peta, routowany do kontrolera PetController oraz funkcji findPet() -->

<h1>Find</h1>
<form action="{{ route('pets.findPet') }}" method="post">
    @csrf
    <label for="petId">ID:</label>
    <input type="number" name="petId" id="petId">
    <button type="submit">Find pet</button>
</form>

<!-- Formularz edytowania Peta, routowany do kontrolera PetController oraz funkcji editPet() -->

<h1>Edit</h1>
<form action="{{ route('pets.editPet') }}" method="post">
    @csrf
    @method('PUT')
    <label for="editId">ID: </label>
    <input type="number" name="editId" id="editId">
    <label for="editName">Name: </label>
    <input type="text" name="editName" id="editName">
    <label for="editStatus">Status: </label>
    <input type="text" name="editStatus" id="editStatus">
    <br> <h3>Category: </h3>
    <label for="editCategoryId">Category ID:</label>
    <input type="number" name="editCategoryId" id="editCategoryId">
    <label for="editCategoryName">Category Name:</label>
    <input type="text" name="editCategoryName" id="editCategoryName">
    <br> <h3>Tag: </h3>
    <label for="editTagId">Tag ID:</label>
    <input type="number" name="editTagId" id="editTagId">
    <label for="editTagName">Tag Name:</label>
    <input type="text" name="editTagName" id="editTagName">
    <button type="submit">Edit pet</button>
</form>

<!-- Formularz usunięcia Peta, routowany do kontrolera PetController oraz funkcji deletePet() -->

<h1>Delete</h1>
<form action="{{ route('pets.deletePet') }}" method="post">
    @csrf
    @method('DELETE')
    <!-- <label for="key">Api key:</label>
    <input type="text" name="key" id="key"> -->
    <label for="delId">ID:</label>
    <input type="number" name="delId" id="delId">
    <button type="submit">Delete pet</button>
</form>