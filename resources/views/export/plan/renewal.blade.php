<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Renewal Plan</title>

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

        table#tableTTD,
        table#tableTTD td {
            border: none;
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
                    <strong>Laporan Plan Renewal</strong>
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
                <td colspan="9">Filter = SBU : {{ $data['sbu'] }} | Total Cost Plan
                    :{{ rupiah($data['total_cost_plan']) }} | Total
                    Data : {{ $data['total_data'] }}</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Document</th>
                <th>SBU</th>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Cost Plan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['transactions'] as $trn)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trn->trn_no }}</td>
                    <td>
                        {{ $trn->document->doc_name }}
                        {{ $trn->document->parent ? '| ' . $trn->document->parent->asset_name : '' }}
                    </td>
                    <td>{{ $trn->sbu->sbu_name ?? '' }}</td>
                    <td>{{ $trn->renewal->name }}</td>
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
                    <b>{{ rupiah($data['total_cost_plan_plan']) }}</b>
                </td> --}}
                <td style="text-align: right">
                    <b>{{ rupiah($data['total_cost_plan']) }}</b>
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
