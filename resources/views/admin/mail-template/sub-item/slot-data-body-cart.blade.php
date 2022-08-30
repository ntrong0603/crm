{{--Slot data body cart--}}

@if (!empty($dataProduct))
    @php
        $mall = $mall ?? 'biz';
        $param = $param ?? '';
    @endphp
    <div style="padding: 15px 10px;"><span style="font-size: 20px;"><strong>お客様がショッピングカートに入れた商品</strong></span>
    </div>
    <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
        <tbody>
            @php
            $countProduct = 0;
            @endphp
            @foreach ($dataProduct as $key => $item)
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
                                                    <strong>
                                                        ¥{{ number_format($item['price']) }} (税込)
                                                    </strong>
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
                                                    style="font-size: 16px; vertical-align: bottom;">{{ $item['rated_time'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}">
                                                <img alt="{{ $item['name'] }}" src="{{ asset('images/btn_big.png') }}"
                                                    style="height: 42px; width: 290px;">
                                            </a>
                                            <br>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                @php
                $countProduct++;
                //nếu số sản phẩm lớn hơn 3 thì dừng
                if($countProduct == 3){
                break;
                }
                @endphp
            @endforeach
        </tbody>
    </table>
    @if (!empty($productRecommend))
        <div style="padding: 15px 10px;">
            <span style="font-size: 14px;">
                <strong>お客様がカートに入れた商品を買った人は、こんな商品も買っています</strong>
            </span>
        </div>
        <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
            <tbody>
                <tr>
                    @php
                    $countCol = 0;
                    @endphp
                    @foreach ($productRecommend as $key => $item)
                        <td style="padding: 10px; width: 33%;">
                            <table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img alt="{{ $item['name'] }}" src="{{ $item['image'] }}"
                                                style="float: left; height: 175px; width: 175px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0px;">
                                            @if (!empty($item['rated']) && !empty($item['rated_time']))
                                                @for ($star = 1; $star <= $item['rated']; $star++)
                                                    <img alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_yellow.png') }}"
                                                        style="height: 13px;">&nbsp;
                                                @endfor
                                                @for ($star = 1; $star <= 5 - $item['rated']; $star++)
                                                    <img alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_gray.png') }}"
                                                        style="height: 13px;">&nbsp;
                                                @endfor
                                                <span style="font-size: 12px;">{{ $item['rated_time'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2
                                                style="color: rgb(0, 0, 255); font-size: 12px; line-height: 18px; margin: 0em; font-weight: 400;">
                                                <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}">
                                                    <span style="color: rgb(0, 0, 255); display: inline-block;">
                                                        <span style="font-size: 12px;">
                                                            {{ $item['name'] }}
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
                                                        style="color: rgb(255, 0, 0);">¥{{ number_format($item['price']) }}</span>
                                                </span>
                                            </strong>&nbsp;
                                            <span style="font-size: 12px;">(税込)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}">
                                                <img alt="{{ $item['name'] }}"
                                                    src="{{ asset('/images/btn_small.png') }}"
                                                    style="display: block; border: 0px; box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 5px; height: 42px; width: 165px;">
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        @php
                        $countCol++;
                        @endphp
                        @if (count($productRecommend) < 3 && $countCol == count($productRecommend))
                            @for ($addTD = 1; $addTD <= 3 - $countCol; $addTD++)
                                <td style="padding: 10px; width: 33%;">
                                </td>
                            @endfor
                        @endif
                        @php
                        if($countCol == 3){
                            break;
                        }
                        @endphp
                    @endforeach
                </tr>
            </tbody>
        </table>
    @endif
@endif
{{--Slot data body cart--}}
