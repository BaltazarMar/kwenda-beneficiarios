<h2>Importar Beneficiários (CSV)</h2>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color: red">{{ session('error') }}</p>
@endif

@if ($errors->any())
    <div style="color:red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="/importar" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Importar CSV</button>
</form>