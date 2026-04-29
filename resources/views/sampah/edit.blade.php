<h1>Edit Sampah</h1>

<form action="/sampah/{{ $data->id }}" method="POST">
    @csrf
    @method('PUT')

    Nama: <input type="text" name="nama" value="{{ $data->nama }}"><br>
    
    Jenis:
    <select name="jenis">
        <option value="organik" {{ $data->jenis == 'organik' ? 'selected' : '' }}>Organik</option>
        <option value="anorganik" {{ $data->jenis == 'anorganik' ? 'selected' : '' }}>Anorganik</option>
    </select><br>

    Harga: <input type="number" name="harga" value="{{ $data->harga }}"><br>

    Deskripsi:
    <textarea name="deskripsi">{{ $data->deskripsi }}</textarea><br>

    <button type="submit">Update</button>
</form>