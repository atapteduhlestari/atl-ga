<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>

    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Renewal Summary</title>

    <style>
        table,
        td,
        th {
            text-align: left;
            border: 1px solid;
            vertical-align: top;
        }

        table {
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
                <strong>Laporan Summary Renewal</strong>
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
        @foreach ($data['transactions'] as $key => $trn)
            @php
                $total_cost = $trn->sum('trn_value');
                $type = $trn->groupBy('renewal.name');
            @endphp
            <tr>
                <td>{{ $key }}</td>
                <td>
                    <ol>
                        @foreach ($type as $t => $val1)
                            <li>{{ $t }}</li>
                        @endforeach
                    </ol>
                </td>
                <td>
                    <ol>
                        @foreach ($type as $k2 => $val2)
                            @php
                                $val2->put('qty', $val2->count());
                            @endphp
                            <li>{{ $val2['qty'] }}</li>
                        @endforeach
                    </ol>
                    <br>
                    Total : {{ $trn->count() }}
                </td>
                <td>
                    <ol>
                        @foreach ($type as $k3 => $val3)
                            @php
                                $val3->put('cost', $val3->sum('trn_value'));
                            @endphp
                            <li>{{ rupiah($val3['cost']) }}</li>
                        @endforeach
                    </ol>
                    <br>
                    Total : {{ rupiah($trn->sum('trn_value')) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: right;"><b>Total</b></td>
            <td><b>QTY: {{ $data['total_qty'] }}</b></td>
            <td><b>Cost: {{ rupiah($data['total_cost']) }}</b></td>
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
