<table width="100%" style="border:1px solid #000" class="table table-bordered">
    <thead>
        <tr>
            <th width="6" style="font-weight:bold">No</th>
            <th width="10" style="font-weight:bold">Email</th>
            <th width="10" style="font-weight:bold">Status</th>
            <th width="10" style="font-weight:bold">Status Verification</th>
            <th width="10" style="font-weight:bold">Date Verification</th>
            <th width="10" style="font-weight:bold">Verified By</th>
            <th width="10" style="font-weight:bold">Date Created</th>
            <th width="10" style="font-weight:bold">File Request</th>

        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $now = date('Y-m-d');
        @endphp
        @foreach ($document as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->email }}</td>
                <td>{{ $val->status_action }}</td>
                <td>{{ $val->status_verification }}</td>
                <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($val->date_verification) }}</td>
                <td>
                    {!! $val->user_verified_id == null ?: $val->user_verified->name !!}
                </td>
                <td>
                    {{ App\Helpers\MyHelper::ubahFormatTimestamp($val->created_at) }}
                </td>

                <td>{{ $val->file }}</td>




            </tr>
        @endforeach
    </tbody>
</table>
