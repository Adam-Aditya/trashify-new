<h1>Tambah Sampah</h1>

<form action="/sampah" method="POST">
    @csrf

    Nama: <input type="text" name="nama"><br>
    
    Jenis:
    <select name="jenis">
        <option value="organik">Organik</option>
        <option value="anorganik">Anorganik</option>
    </select><br>

    Harga: <input type="number" name="harga"><br>

    Deskripsi:
    <textarea name="deskripsi"></textarea><br>

    <button type="submit">Simpan</button>
</form>