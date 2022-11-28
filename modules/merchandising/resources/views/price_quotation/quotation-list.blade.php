<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable ">
            <thead>
            <tr class="table-header">
               
               
                <th>Company</th>
                <th>Buyer</th>
                <th>Quotation Id</th>
                <th>Inquiry Id</th>
                <th>Product Dept.</th>
                <th>Style</th>
                <th>Offer Qty.</th>
                <th>Uom</th>
                <th>Price/DZN</th>
                <th>Price/PCS</th>
                <th>Season</th>
                <th>Size Group</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody class="company-list">
            @if(!$price_quotations->isEmpty())
                @foreach($price_quotations as $price_quotation)
                        <?php
                        $tooltipInfo = "<div class='tooltip-info'><span><strong>Created by: </strong>" . $price_quotation->createdBy->screen_name . "</span><br><span><strong>Created at: </strong>" . date("F j, Y, g:i a", strtotime($price_quotation->created_at)) . "</span><br><span><strong>Updated at: </strong>" . date("F j, Y, g:i a", strtotime($price_quotation->updated_at)) . "</span></div>";
                        ?>
                    <tr data-html=true data-toggle="tooltip" data-placement="top" title="{{ $tooltipInfo }}"
                        class="tooltip-data">
                        <td>{{ $price_quotation->factory->factory_short_name ?? $price_quotation->factory->factory_name}}</td>
                        <td>{{ $price_quotation->buyer->name }}</td>
                        <td>{{ $price_quotation->quotation_id }}</td>
                        <td>{{ $price_quotation->quotationInquiry->quotation_id }}</td>
                        <td>{{ $price_quotation->productDepartment->product_department }}</td>
                        <td>{{ $price_quotation->style_name }}</td>
                        <td>{{ $price_quotation->offer_qty }}</td>
                        <td>{{ $price_quotation->style_uom_name }}</td>
                        <td>{{ getCurrencySign(strtolower($price_quotation->currency->currency_name)) }}{{ $price_quotation->price_with_commn_dzn ?? 0 }}</td>
                        <td>{{ getCurrencySign(strtolower($price_quotation->currency->currency_name)) }}{{ $price_quotation->confirm_price_pc_set ?? 0 }}</td>
                        <td>{{ $price_quotation->season->season_name }}</td>
                        <td>{{ $price_quotation->season_grp }}</td>
                        <td>
                            @if($price_quotation->is_approve == 1)
                                approved
                            @elseif($price_quotation->step > 0 || $price_quotation->ready_to_approve == 1)
                               Ready To Approve
                            @elseif($price_quotation->ready_to_approved != 'Yes')
                                Not Ready To Approve
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="14" align="center">No Data</td>
                </tr>
            @endif
            </tbody>

        </table>
    </div>
</div>
