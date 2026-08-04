<table width="100%" style="border:1px solid #000" class="table table-bordered">
    <thead>
        <tr>
            <th width="6" style="font-weight:bold">No</th>
            <th width="10" style="font-weight:bold">Tipe Dokumen</th>
            <th width="10" style="font-weight:bold">Status</th>
            <th width="10" style="font-weight:bold">Judul Existing Document</th>
            <th width="10" style="font-weight:bold">Tujuan</th>
            <th width="10" style="font-weight:bold">PIC</th>
            <th width="10" style="font-weight:bold">Created By</th>



        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $now = date('Y-m-d');
        @endphp
        @foreach ($document['request'] as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->type }}</td>
                <td>
                    @if ($val->is_cancel == 1)
                        Cancel
                    @else
                        @if ($val->status == 1)
                            Submitted
                        @elseif ($val->status == 2)
                            Need Upload
                            Document
                        @elseif ($val->status == 3)
                            Uploaded, Need
                            Confirmation
                        @elseif ($val->status == 4)
                            Reject, Need
                            Reupload
                        @elseif ($val->status == 5)
                            Done
                        @endif
                    @endif
                </td>
                <td>
                    @foreach ($val->RequestExistingDocument as $key => $rd)
                        {{ $rd->title }}
                        @if ($key < count($val->RequestExistingDocument) - 1)
                            ,
                        @endif
                    @endforeach



                </td>
                <td>{{ $val->purpose }}</td>
                <td>
                    {{ $val->Pic->name }}
                </td>

                <td>
                    {{ getUserName($val->created_by)->name }}
                </td>



            </tr>
        @endforeach
    </tbody>
</table>
