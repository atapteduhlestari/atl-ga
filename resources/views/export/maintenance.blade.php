<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Maintenance Detail</title>

    {{-- <style>
        table,
        td,
        th {
            border: 1px solid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table#tableTTD,
        table#tableTTD td {
            border: none;
        }
    </style> --}}
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="2">{{ now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:12;">
                    <strong>PT ATAP TEDUH LESTARI</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:12;">
                    <strong>Laporan Detail Maintenance</strong>
                </td>

            </tr>
            <tr>
                <td colspan="2" style="font-size:12;">
                    <strong>Periode : {{ $data['periode'] }}</strong>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="12">Filter = SBU : {{ $data['sbu'] }}| Status :
                    {{ $data['status'] }}| Total Cost
                    :{{ rupiah($data['total_cost']) }} | Total
                    Data : {{ $data['total_data'] }}</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Asset</th>
                <th>SBU</th>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Cost</th>
                <th>Applicant</th>
                <th>Approval</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['transactions'] as $trn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trn->trn_no }}</td>
                    <td>{{ $trn->asset->asset_name }}</td>
                    <td>{{ $trn->sbu->sbu_name ?? '' }}</td>
                    <td>{{ $trn->maintenance->name }}</td>
                    <td>{{ strip_tags($trn->trn_desc) }}</td>
                    <td>{{ createDate($trn->trn_start_date)->format('d/m/Y') }}</td>
                    <td>{{ createDate($trn->trn_date)->format('d/m/Y') }}</td>
                    <td style="text-align: right">{{ rupiah($trn->trn_value) }}</td>
                    <td>{{ $trn->pemohon }}</td>
                    <td>{{ $trn->penyetuju }}</td>
                    <td>{{ $trn->trn_status ? 'Closed' : 'Open' }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <b>Total</b>
                </td>
                {{-- <td>
                    <b>{{ rupiah($data['total_cost_plan']) }}</b>
                </td> --}}
                <td style="text-align: right">
                    <b>{{ rupiah($data['total_cost']) }}</b>
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <table id="tableTTD">
        <tr>
            <td style="text-align: center">
                Pembuat,
            </td>
            <td style="text-align: center">
                Mengetahui,
            </td>
            <td style="text-align: center">
                Menyetujui,
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td style="text-align:center;">
                (.............................)
            </td>
            <td style="text-align:center;">
                (.............................)
            </td>
            <td style="text-align:center;">
                (.............................)
            </td>
        </tr>
    </table>
</body>

</html>
