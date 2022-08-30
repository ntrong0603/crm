@if (empty($keyTemplate))
    {{--Slot data body--}}
    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="slot-box slot" style="border-spacing: 0;">
        <tbody>
            <tr>
                <td class="active" style="padding:10px;">
                    <table border="0" width="100%" cellpadding="0" cellspacing="0"
                        class="slot-content columns-container" style="border-spacing:0;">
                        <tbody>
                            @php
                            $countItemRow = 0;
                            $countRow = 0;
                            @endphp
                            <tr>
                                @foreach ($dataProduct as $item)
                                    {{--▼colume1--}}
                                    <td class="force-col" style="padding-left:10px;padding-right:10px;" valign="top">

                                        <table border="0" cellspacing="0" cellpadding="0" width="175" align="left"
                                            class="col3-set">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="top"
                                                        style="font-size:16px;line-height:1.6;">
                                                        <div class="edit-wrap" style="">
                                                            <div class="edit-box">
                                                                <img src="{{ $item['image'] }}"
                                                                    alt="{{ $item['name'] }}" width="175" border="0"
                                                                    hspace="0" vspace="0"
                                                                    style="vertical-align:bottom;margin:5px;"
                                                                    class="col3-set-img">
                                                            </div>
                                                        </div>
                                                        <div class="edit-wrap">
                                                            <div class="edit-box">
                                                                <table border="0" cellpadding="1" cellspacing="1"
                                                                    style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <h2
                                                                                    style="color: rgb(0, 0, 255); font-size: 12px; line-height: 18px; margin: 0em; font-weight: 400;">
                                                                                    <a
                                                                                        href="https://shop.diyfactory.jp/product/{{ $item['model'] }}/"><span
                                                                                            style="color: rgb(0, 0, 255); display: inline-block;"><span
                                                                                                style="font-size: 12px;">{{ $item['name'] }}</span></span></a>
                                                                                </h2>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong><span
                                                                                        style="font-size: 16px;"><span
                                                                                            style="color: rgb(255, 0, 0);">&yen;{{ number_format($item['price'], 1) }}</span></span></strong>&nbsp;<span
                                                                                    style="font-size: 12px;">(税込)</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><a
                                                                                    href="https://shop.diyfactory.jp/product/{{ $item['model'] }}/"><img
                                                                                        alt=""
                                                                                        src="{{ asset('images/btn_small.png') }}"
                                                                                        style="display: block; border: 0px; box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 5px; height: 42px; width: 165px;" /></a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    {{--▲column1--}}
                                    @php
                                    $countItemRow++;
                                    @endphp
                                    @if ($countItemRow == 3 && $countRow < 6) @php
                                        $countRow++; @endphp
                            </tr>
                            <tr>
                            @elseif($countRow == 6)
                                @php
                                break;
                                @endphp
@endif
@endforeach
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
{{--Slot data body--}}
@endif
