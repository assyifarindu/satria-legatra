<table width="100%" style="border:1px solid #000" class="table table-bordered">
    <thead>
        <tr>
            <th width="6" style="font-weight:bold">No</th>
            <th width="10" style="font-weight:bold">Contract Number</th>
            <th width="10" style="font-weight:bold">Judul Contract/Letter</th>
            <th width="10" style="font-weight:bold">Category</th>
            <th width="10" style="font-weight:bold">Company</th>
            <th width="10" style="font-weight:bold">Tipe</th>
            <th width="10" style="font-weight:bold">Tipe HAKI</th>
            <th width="10" style="font-weight:bold">Duty</th>
            <th width="10" style="font-weight:bold">Launch By</th>
            <th width="10" style="font-weight:bold">Effective Date</th>
            <th width="10" style="font-weight:bold">End Contract Date</th>
            <th width="10" style="font-weight:bold">Duration</th>
            <th width="10" style="font-weight:bold">PIC</th>
            <th width="10" style="font-weight:bold">Department In Charge</th>
            <th width="10" style="font-weight:bold">Is Unlimited Duration</th>
            <th width="10" style="font-weight:bold">Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $now = date('Y-m-d');
        @endphp
        @foreach ($document as $val)
            @php
                $end_date = $val->end_contract_date;
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->contract_number }}</td>
                <td>{{ $val->description }}</td>
                <td>Contract/Letter</td>
                <td>{{ $val->company }}</td>
                <td>{{ getContractType($val->id) }}</td>

                <td>{{ $val->HakiType->name }}</td>
                <td>{{ $val->Duty->name }}</td>
                <td>{{ $val->launch_by }}</td>
                {{-- 
                <td>{{ $val->contract_date }}</td>
                <td>{{ $val->end_contract_date }}</td> --}}
                <td>{{ \Carbon\Carbon::parse($val->contract_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($val->end_contract_date)->format('d-m-Y') }}</td>
                <td>{{ $val->duration_days }} Days</td>
                <td>
                    @foreach ($val->pic as $ke => $item)
                        {{ $item->name }}
                        @if ($ke < count($val->pic) - 1)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($val->document_scope as $key => $ds)
                        {{ $ds->department_name }}
                        @if ($key < count($val->document_scope) - 1)
                            ,
                        @endif
                    @endforeach
                </td>
                <td>
                    @if ($val->is_unlimited_duration == 1)
                        Iya
                    @else
                        Tidak
                    @endif
                </td>
                <td>
                    @if ($end_date < $now)
                        Tidak Aktif
                    @else
                        Aktif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
