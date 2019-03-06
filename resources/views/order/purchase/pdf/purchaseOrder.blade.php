<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>The Techknow Space - #{{$purchaseOrder->number}}</title>

    <style type="text/css">
        @page {
            margin: 10px;
        }
        body {
            margin: 10px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
            margin-right: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 33%;">
                <h3>The Techknow Space</h3>
                <pre>
33, City Center Drive
Missisuaga, ON
Canada
<br /><br />
Date: March-05-2019
</pre>


            </td>
            <td align="center">
                <h3>PO#: {{$purchaseOrder->number}}</h3>
                Ordered <br> SKUs:{{$purchaseOrder->PurchaseOrderItems->count()}} | Qty: {{$purchaseOrder->PurchaseOrderItems->sum('qty')}}
            </td>
            <td align="right" style="width: 33%;">

                <h3>REWA</h3>
                <pre>
                    www.rewatechnology.com
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">

    <table width="100%">
        <thead>
        <tr>
            <th style="width: 8%">
                #
            </th>
            <th style="width: 12%">
                SKU
            </th>
            <th style="width: 10%">
                Brand
            </th>
            <th style="width: 25%">
                Model
            </th>
            <th style="width: 40%">
                Part
            </th>
            <th style="width: 5%">
                Qty
            </th>
        </tr>
        </thead>
        <tbody>

            <?php $j = 1 ?>
            @foreach($parts as $po_item)
                <tr>
                    <td style="width: 8%">
                        {{$j}}
                    </td>
                    <td style="width: 12%">
                        {{$po_item->Part->sku}}
                    </td>
                    <td style="width: 10%">
                        {{$po_item->Part->devices->brand->name}}
                    </td>
                    <td style="width: 25%">
                        {{$po_item->Part->devices->model_name}}
                    </td>
                    <td style="width: 40%">
                        {{$po_item->Part->part_name}}
                    </td>
                    <td style="width: 5%">
                        {{$po_item->qty}}
                    </td>
                    <?php $j++; ?>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td align="left" class="gray"></td>
            <td align="left">{{$purchaseOrder->PurchaseOrderItems->sum('qty')}} </td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} <a href="https://techknowspace.com">techknowspace.com</a> - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                Technology Meets Knowledge
            </td>
        </tr>

    </table>
</div>
</body>
</html>
