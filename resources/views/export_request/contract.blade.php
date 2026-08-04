<table width="100%" style="border:1px solid #000" class="table table-bordered">
    <thead>
        <tr>
            <th width="6" style="font-weight:bold">No</th>
            <th width="10" style="font-weight:bold">Contract Number</th>
            <th width="10" style="font-weight:bold">Jenis</th>
            <th width="10" style="font-weight:bold">Status</th>
            <th width="10" style="font-weight:bold">Judul Contract/Letter</th>
            <th width="10" style="font-weight:bold">Para Pihak</th>
            <th width="10" style="font-weight:bold">Ruang Lingkup</th>
            <th width="10" style="font-weight:bold">Jenis Request</th>
            <th width="10" style="font-weight:bold">Send Email</th>
            <th width="10" style="font-weight:bold">Created By</th>
            <th width="10" style="font-weight:bold">SLA </th>

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
                <td>{{ $val->contract_number }}</td>
                <td>{{ $val->type }}</td>
                <td>
                    @if ($val->is_cancel == 1)
                        Cancel
                    @else
                        @if ($val->status == 0)
                            Submitted
                        @elseif ($val->status == 1)
                            Legal Drafting
                        @elseif ($val->status == 2)
                            Send Draft
                        @elseif ($val->status == 3)
                            Feedback
                        @elseif ($val->status == 4)
                            Revisi
                        @elseif ($val->status == 5)
                            Send Rekanan
                        @elseif ($val->status == 6)
                            Negosiasi
                        @elseif ($val->status == 7)
                            Filing
                        @endif
                    @endif
                </td>
                <td>{{ $val->title }}</td>
                <td>
                    @foreach (getPihak($val->id) as $key => $i)
                        {{ $i->name }}
                        @if ($key < count(getPihak($val->id)) - 1)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>{{ $val->scope }}</td>

                <td>
                    @if ($val->is_extend == 1)
                        Extend Document
                    @else
                        New Document
                    @endif
                </td>
                <td>
                    {{ $val->email }}
                </td>
                <td>
                    {{ getUserName($val->created_by)->name }}
                </td>
                <td>
                    @if ($val->status == 7)
                        {{ getSlaRequestDocument($val->id) }} Hari
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
