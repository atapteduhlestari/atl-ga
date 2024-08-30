<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>

    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Maintenance Summary</title>

    <style>
        table,
        td,
        th {
            text-align: left;
            border: 1px solid black;
            border-collapse: collapse;
        }

        table#tableTTD,
        table#tableTTD td {
            border: none;
        }
    </style>
</head>

<body>
    <table class="table table-bordered">
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
                <strong>Laporan Summary Maintenance</strong>
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
            <td colspan="5">
                Filter = Total Cost: {{ rupiah($data['total_cost']) }} | Total QTY:
                {{ $data['total_qty'] }} |
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <th>SBU</th>
            <th>Type</th>
            <th>QTY</th>
            <th>Cost</th>
        </tr>
        @foreach ($data['transactions'] as $sbu => $trn)
            @php
                $total_cost = $trn->sum('trn_value');
                $type = $trn->groupBy('maintenance.name');
            @endphp
            @foreach ($type as $t => $val1)
                @php
                    $val1->put('qty', $val1->count());
                    $val1->put('cost', $val1->sum('trn_value'));
                @endphp
                <tr>
                    <td>
                        {{ $sbu }}
                    </td>
                    <td>
                        {{ $t }}
                    </td>
                    <td>
                        {{ $val1['qty'] }}
                    </td>
                    <td style="text-align:right">
                        {{ rupiah($val1['cost']) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align:right"><b>Total: {{ $trn->count() }}</b></td>
                <td style="text-align:right">
                    <b>Total:{{ rupiah($trn->sum('trn_value')) }}</b>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: right;"><b>Total</b></td>
            <td style="text-align: right;"><b>QTY: {{ $data['total_qty'] }}</b></td>
            <td style="text-align: right;">
                <b>Cost: {{ rupiah($data['total_cost']) }}</b>
            </td>
        </tr>
    </table>
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
