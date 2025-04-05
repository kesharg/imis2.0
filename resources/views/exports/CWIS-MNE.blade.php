<table>
    <thead>
        <tr>
            <th>CWIS Monitoring and Evaluation Indicator {{ $years }}</th>
        <tr>
            <th>Indicator </th>
            <th>Outcome</th>
            <th>Value</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($results as $result)
            <tr>
                <td>{{ $result->assmntmtrc_dtpnt}}</td>
                <td> {{ $result->co_cf }}</td>
                <td>{{ $result->data_value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
