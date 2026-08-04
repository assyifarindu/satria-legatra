<table width="100%" style="border:1px solid #000" class="table table-bordered">
    <thead>
        <tr>
            <th width="6" style="font-weight:bold">No</th>
            <th width="10" style="font-weight:bold">Jenis</th>
            <th width="10" style="font-weight:bold">Status</th>
            <th width="10" style="font-weight:bold">Judul License</th>
            <th width="10" style="font-weight:bold">Ruang Lingkup</th>
            <th width="10" style="font-weight:bold">Jenis Request</th>
            <th width="10" style="font-weight:bold">Send Email</th>
            <th width="10" style="font-weight:bold">Created By</th>
            <th width="10" style="font-weight:bold">SLA</th>




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
                <td>{{ $val->type }}</td>
                <td>
                    @if ($val->is_cancel)
                        <span class="badge bg-danger">Cancel</span>
                    @else
                        @if ($val->status == 0)
                            <span class="badge bg-primary">Submitted</span>
                        @elseif ($val->status == 1)
                            <span class="badge bg-primary">Preparation</span>
                        @elseif ($val->status == 2)
                            <span class="badge bg-primary">Registration</span>
                        @elseif ($val->status == 3)
                            <span class="badge bg-primary">Complete</span>
                        @elseif ($val->status == 4)
                            <span class="badge bg-success">Filing</span>
                        @endif
                    @endif
                </td>
                <td>{{ $val->title }}</td>

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
                    @if ($val->status == 4)
                        {{ getSlaRequestDocumentFour($val->id) }} Hari
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
