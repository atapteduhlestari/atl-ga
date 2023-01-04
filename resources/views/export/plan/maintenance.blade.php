<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Maintenance Plan</title>

    <style>
        table,
        td,
        th {
            border: 1px solid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
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
                    <strong>Laporan Plan Maintenance</strong>
                </td>

            </tr>
            <tr>
                <td colspan="2" style="font-size:12;">
                    <strong>Periode : {{ $transactions['periode'] }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="9">&nbsp; </td>
            </tr>
            <tr>
                <td colspan="9">Filter = SBU : {{ $transactions['sbu'] }} | Total Cost Plan
                    :{{ rupiah($transactions['total_cost_plan']) }} | Total
                    Data : {{ $transactions['total_data'] }}</td>
            </tr>
            <tr>
                <td colspan="9">&nbsp;</td>
            </tr>
            {{-- <tr>
                <td colspan="13">&nbsp;</td>
            </tr> --}}
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Asset</th>
                <th>SBU</th>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Cost Plan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions['transactions'] as $trn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trn->trn_no }}</td>
                    <td>{{ $trn->asset->asset_name }}</td>
                    <td>{{ $trn->sbu->sbu_name ?? '' }}</td>
                    <td>{{ $trn->maintenance->name }}</td>
                    <td>{{ strip_tags($trn->trn_desc) }}</td>
                    <td>{{ createDate($trn->trn_start_date)->format('d/m/Y') }}</td>
                    <td>{{ createDate($trn->trn_date)->format('d/m/Y') }}</td>
                    <td style="text-align: right">{{ rupiah($trn->trn_value_plan) }}</td>
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
                    <b>{{ rupiah($transactions['total_cost_plan_plan']) }}</b>
                </td> --}}
                <td style="text-align: right">
                    <b>{{ rupiah($transactions['total_cost_plan']) }}</b>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
