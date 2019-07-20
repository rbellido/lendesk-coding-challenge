<html>
<head>
    <title>Lendesk Coding Challenge</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    {{ $header }}
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($exifs as $exifData)
                <tr>
                    <td>{{ $exifData->getName() }}</td>
                    <td>{{ $exifData->getLatitude() ?? '' }}</td>
                    <td>{{ $exifData->getLongitude() ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>