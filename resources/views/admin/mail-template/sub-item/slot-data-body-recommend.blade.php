{{--Slot data body recommed--}}
@php
$mall = $mall ?? 'biz';
$param = $param ?? '';
@endphp
<div style="padding: 15px 10px;"><span style="font-size: 20px;"><strong>お客様にオススメの商品</strong></span>
</div>
<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
    <tbody>
        @if (!empty($dataProduct))
            @foreach ($dataProduct as $item)
                <tr>
                    <td style="vertical-align: top; padding-left: 15px; padding-right: 15px; padding-bottom: 15px;">
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
                                        <td style="padding-top: 10px; padding-bottom: 10px;">
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
                                        <td style="padding-bottom: 10px;">
                                            @if (!empty($item['rated']) && !empty($item['rated_time']))
                                                @for ($star = 1; $star <= $item['rated']; $star++) <img
                                                        alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_yellow.png') }}"
                                                        style="height: 18px;">
                                                    &nbsp;
                                                @endfor
                                                @for ($star = 1; $star <= 5 - $item['rated']; $star++) <img
                                                        alt="rating-{{ $item['name'] }}-{{ $star }}"
                                                        src="{{ asset('images/review_rating_gray.png') }}"
                                                        style="height: 18px;">
                                                    &nbsp;
                                                @endfor
                                                <span
                                                    style="font-size: 16px; vertical-align: bottom;">{{ $item['rated_time'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="    padding-bottom: 30px;">
                                            <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}">
                                                <img alt="btn-{{ $item['name'] }}"
                                                    src="{{ asset('images/btn_big.png') }}"
                                                    style="height: 42px; width: 290px;">
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{--Slot data body recommed--}}
