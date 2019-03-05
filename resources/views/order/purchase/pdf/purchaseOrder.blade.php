<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>The Techknow Space - #{{$purchaseOrder->number}}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
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
            <td align="left" style="width: 40%;">
                <h3>The Techknow Space</h3>
                <pre>
3, City Center Drive
Missisuaga, ON
Canada
<br /><br />
Date: {{$purchaseOrder->updated_at}}
Status: {{$purchaseOrder->PurchaseOrderStatus->status}}
</pre>


            </td>
            <td align="center">
                <h3>PO ID: {{$purchaseOrder->id}}</h3>
            </td>
            <td align="right" style="width: 40%;">

                <h3>{{$purchaseOrder->Supplier->name}}</h3>
                <pre>
                    https://company.com
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>#{{$purchaseOrder->number}}</h3>
    <table width="100%">
        <thead>
        <tr>
            <th style="width: 10%">
                SKU
            </th>
            <th style="width: 70%">
                Part Name
            </th>
            <th style="width: 10%">
                Cost
            </th>
            <th style="width: 10%">
                Qty
            </th>
        </tr>
        </thead>
        <tbody>

        <?php $i = 0; ?>
        @foreach($purchaseOrder->PurchaseOrderItems->chunk(38) as $po_items_page)
            @if($i == 2)
                <?php break; ?>
            @endif
            @foreach($po_items_page as $po_item)
                <tr>
                    <td style="width: 15%">
                        {{$po_item->Part->sku}}
                    </td>
                    <td style="width: 65%">
                        {{$po_item->Part->devices->brand->name}} {{$po_item->Part->devices->model_name}} {{$po_item->Part->part_name}}
                    </td>
                    <td style="width: 10%">
                        {{$po_item->cost}}
                    </td>
                    <td style="width: 10%">
                        {{$po_item->qty}}
                    </td>

                </tr>
            @endforeach
            <?php $i++ ?>

        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td align="left" class="gray">${{$purchaseOrder->PurchaseOrderItems->sum('total_cost')}} </td>
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
