<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }
        #back-wrap {
            margin: 20px auto;
            width: 500px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-back, .btn-print {
            font-size: 0.9rem;
            padding: 10px 20px;
            color: #fff;
            background-color: #3498db;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
            justify-content:space-between;
        }
        .btn-back:hover, .btn-print:hover {
            background-color: #2980b9;
        }
        #receipt {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 30px auto;
            width: 500px;
            background-color: #fff;
            border-radius: 10px;
        }
        h2 {
            font-size: 1.1rem;
            color: #333;
        }
        p {
            font-size: 0.9rem;
            color: #555;
            line-height: 1.5;
        }
        #top {
            margin-top: 20px;
            text-align: center;
        }
        .info h2 {
            margin: 0;
        }
        .info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .tabletitle {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .service, .tabletitle {
            font-size: 0.9rem;
        }
        .itemtext {
            color: #333;
        }
        #legalcopy {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #777;
            justify-content: center;
        }
        .payment {
            font-weight: bold;
            color: #e74c3c;
        }

        @media print {
           .btn-print, .btn-back {
              visibility: hidden;
           }
        }
    </style>
</head>
<body>
    <div id="back-wrap">
        <a href="{{ route('pembelian.order') }}" class="btn-back">Kembali</a>
    </div>

    <div id="receipt">
        <center id="top">
            <a href="{{route('pembelian.download_pdf', $order['id'])}}">Cetak pdf</a>
            <div class="info">
                <h2>Apotek Jaya Abadi</h2>
            </div>
        </center>


        <div id="bot">
            <div id="table">
                <table>
                    <tr class="tablettile">
                        <td class="item">
                            <h2>Medicine</h2>
                        </td>
                        <td class="item">
                            <h2>Total</h2>
                        </td>
                        <td class="rate">
                            <h2>Price</h2>
                        </td>
                    </tr>
                    @foreach ($order['medicines'] as $item)
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">{{ $item['name_medicine'] }}</p></td>
                        <td class="tableitem"><p class="itemtext">{{ $item['qyt'] }}</p></td>
                        <td class="tableitem"><p class="itemtext">Rp {{ $item['price'],0,',','.' }}</p></td>
                    </tr>
                    @endforeach
                    <tr class="tabletitle">
                        <td></td>
                        <td class="rate">
                            <h2>PPN 10%</h2>
                        </td>
                        @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp
                        <td class="payment">
                            <h2>Rp {{ number_format($ppn, 0, ',', '.') }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td></td>
                        <td class="rate">
                            <h2>Total</h2>
                        </td>
                        <td class="payment">
                            <h2>Rp {{ number_format($order['total_price'] + $ppn, 0, ',', '.') }}</h2>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="legalcopy">
                <p class="legal"><strong>Thank you for your business!</strong> Invoice was created on a computer and is valid without the signature and seal.</p>
            </div>
            <div class="info" id="mid">
                <p>Alamat: hati-hati di jalan<br>
                Email: apoteker sigma@gmail.com<br>
                Phone: 000-111-2222</p>
            </div>
        </div>
    </div>
</body>
</html>
