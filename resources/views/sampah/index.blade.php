<h1>Data Sampah</h1>

<a href="/sampah/create">Tambah Data</a>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Jenis</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

    @foreach($data as $d)
    <tr>
        <td>{{ $d->nama }}</td>
        <td>{{ $d->jenis }}</td>
        <td>{{ $d->harga }}</td>
        <td>
            <a href="/sampah/{{ $d->id }}/edit">Edit</a>

            <form action="/sampah/{{ $d->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>