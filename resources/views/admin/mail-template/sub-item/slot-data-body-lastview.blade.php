{{--Slot data body lastview--}}
@if (!empty($dataProduct))
@php
$mall = $mall ?? 'biz';
$param = $param ?? '';
@endphp
    @foreach ($dataProduct as $key => $item)
        <div style="padding: 15px 10px;"><span style="font-size: 20px;"><strong>お客様が最近ご覧になった商品</strong></span>
        </div>
        <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; padding-left: 15px; padding-right: 15px; padding-bottom: 30px;">
                        <img alt="{{ $item['name'] }}" src="{{ $item['image'] }}" style="width: 175px; height: 175px;">
                    </td>
                    <td style="vertical-align: top; padding-left: 30px; padding-right: 15px;">
                        <div>
                            <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h2 style="margin: 0px;">
                                                <strong>
                                                    <span style="font-size: 16px;">
                                                        <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}" target="_blank">
                                                            <span style="color: rgb(0, 0, 255);">
                                                                {{ $item['name'] }}
                                                            </span>
                                                        </a>
                                                    </span>
                                                </strong>
                                            </h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 15px; padding-bottom: 15px;">
                                            <span style="font-size: 18px;">価格:
                                                <span style="color: rgb(255, 0, 0);">
                                                    <strong>¥{{ number_format($item['price']) }} (税込)</strong>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 15px;">
                                            @if (!empty($item['rated']) && !empty($item['rated_time']))
                                                @for ($star = 1; $star <= $item['rated']; $star++)
                                                    <img alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_yellow.png') }}"
                                                        style="height: 18px;">&nbsp;
                                                @endfor
                                                @for ($star = 1; $star <= 5 - $item['rated']; $star++)
                                                    <img alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_gray.png') }}"
                                                        style="height: 18px;">&nbsp;
                                                @endfor
                                                <span
                                                    style="font-size: 16px; vertical-align: bottom;">{{ $item['rated_time'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}">
                                                <img alt="{{ $item['name'] }}" src="{{ asset('images/btn_big.png') }}"
                                                    style="height: 42px; width: 290px;">
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @if (!empty($productRecommend[$item['model']]))
            <div style="padding: 15px 10px;"><span style="font-size: 14px;"><strong>この商品はこんな商品と比較されています</strong></span>
            </div>
            <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
                <tbody>
                    <tr>
                        @foreach ($productRecommend[$item['model']] as $key => $child)
                            <td style="padding: 10px; width: 33%;">
                                <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img alt="{{ $child['name'] }}" src="{{ $child['image'] }}"
                                                    style="float: left; height: 175px; width: 175px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0px;">
                                                @if (!empty($child['rated']) && !empty($child['rated_time']))
                                                    @for ($star = 1; $star <= $child['rated']; $star++)
                                                        <img alt="rating-{{ $child['name'] }}-{{ $star }}"
                                                            src="{{ asset('images/review_rating_yellow.png') }}"
                                                            style="height: 14px;">&nbsp;
                                                    @endfor
                                                    @for ($star = 1; $star <= 5 - $child['rated']; $star++)
                                                        <img alt="rating-{{ $child['name'] }}-{{ $star }}"
                                                            src="{{ asset('images/review_rating_gray.png') }}"
                                                            style="height: 14px;">&nbsp;
                                                    @endfor
                                                    <span style="font-size: 12px;">{{ $child['rated_time'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2
                                                    style="color: rgb(0, 0, 255); font-size: 12px; line-height: 18px; margin: 0em; font-weight: 400;">
                                                    <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($child['model']), $mall, $param) }}">
                                                        <span style="color: rgb(0, 0, 255); display: inline-block;">
                                                            <span style="font-size: 12px;">
                                                                {{ $child['name'] }}
                                                            </span>
                                                        </span>
                                                    </a>
                                                </h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px 0px;">
                                                <strong>
                                                    <span style="font-size: 16px;">
                                                        <span
                                                            style="color: rgb(255, 0, 0);">¥{{ number_format($child['price']) }}
                                                        </span>
                                                    </span>
                                                </strong>&nbsp;
                                                <span style="font-size: 12px;">(税込)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($child['model']), $mall, $param) }}">
                                                    <img alt="btn-{{ $child['name'] }}"
                                                        src="{{ asset('/images/btn_small.png') }}"
                                                        style="display: block; border: 0px; box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 5px; height: 42px; width: 165px;">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        @endforeach
                        @if (count($productRecommend[$item['model']]) < 3 && ($key + 1) == count($productRecommend[$item['model']]))
                            @for ($addTD = 1; $addTD <= 3 - count($productRecommend[$item['model']]); $addTD++)
                                <td style="padding: 10px; width: 33%;">
                                </td>
                            @endfor
                        @endif
                    </tr>
                </tbody>
            </table>
        @endif
        <!-- line -->
        @if ($key + 1 < count($dataProduct))
            <div style="padding: 33px 0 ;">
                <hr style="width: 100%; height: 1px; border: 0; background-color: #adadad;">
            </div>
        @endif
        <!-- line -->
    @endforeach
@endif
{{--Slot data body lastview--}}
