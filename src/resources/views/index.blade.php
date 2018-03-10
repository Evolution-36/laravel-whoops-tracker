@extends('whoops-tracker::layouts.basic')

@section('sections')
    <section>
        <table border="1">
            <tr>
                <th>File</th>
                <th>Line</th>
                <th>Message</th>
                <th>Count</th>
            </tr>
            @foreach ($whoopses as $whoops)
                <tr>
                    <td>{{ $whoops->file }}</td>
                    <td>{{ $whoops->line }}</td>
                    <td>{{ $whoops->message }}</td>
                    <td>{{ $whoops->lwtOccurrences->count() }}</td>
                </tr>
            @endforeach
        </table>
    </section>
@endsection