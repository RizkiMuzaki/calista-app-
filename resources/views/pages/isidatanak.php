<!doctype html>
<html>
<head>
    <title>Data Anak</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <h1>Data Anak</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Anak</th>
                <th>Tanggal Lahir</th>
                <th>Limit Detik</th>
                <th>Sisa Detik</th>
                <th>Tanggal Reset</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anaks as $anak)
                <tr>
                    <td>{{ $anak->nama_anak }}</td>
                    <td>{{ $anak->tanggal_lahir }}</td>
                    <td>{{ $anak->limit_detik }}</td>
                    <td>{{ $anak->sisa_detik }}</td>
                    <td>{{ $anak->tanggal_reset }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
