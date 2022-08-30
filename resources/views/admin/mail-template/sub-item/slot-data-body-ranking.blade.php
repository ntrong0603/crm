{{--Slot data body ranking--}}
@php
$mall = $mall ?? 'biz';
$param = $param ?? '';
@endphp
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td style="padding: 25px 0px 25px 7px; font-size: 20px;">
                <b>今週のカテゴリ別売筋ランキング</b></td>
        </tr>

        @if (!empty($dataProduct))

            @foreach ($dataProduct as $category)

                @if (!empty($category['products']))
                    <tr>
                        <td style="padding: 0px 0px 15px 7px; font-size: 14px;">
                            <b>{{ $category['name_category'] }}ランキング</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px 0px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        @foreach ($category['products'] as $key => $item)
                                            <td valign="top" width="33.333333%"
                                                style="padding-left: 10px; padding-right: 10px;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding: 15px 0px 2px;">
                                                                <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}"
                                                                    rel="noopener" target="_blank">
                                                                    <img alt="{{ $item['name'] }}"
                                                                        src="{{ $item['image'] }}"
                                                                        style="display: block; margin: auto; width: 100%; height: auto;">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0px 0px 5px; height: 20px;">
                                                                <p style="font-size: 12px; margin: 0px;">
                                                                    <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}"
                                                                        rel="noopener" target="_blank">
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
                                                                            &nbsp;
                                                                            <span
                                                                                style="display: inline-block; vertical-align: bottom; line-height: 1.1; color: rgb(0, 0, 0);">
                                                                                {{ $item['rated_time'] }}
                                                                            </span>
                                                                        @endif

                                                                    </a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0px 0px 10px; height: 95px; vertical-align: text-top;">
                                                                <h2
                                                                    style="color: rgb(0, 0, 255); font-size: 12px; line-height: 18px; margin: 0px; font-weight: 400;">
                                                                    <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}"
                                                                        rel="noopener" style="display: inline-block;"
                                                                        target="_blank">
                                                                        {{ $item['name'] }}
                                                                    </a>
                                                                </h2>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0px 0px 14px 5px;">
                                                                <p style="margin: 0px;">
                                                                    <span
                                                                        style="color: rgb(255, 0, 0); font-size: 16px; font-weight: 700;">
                                                                        ¥{{ number_format($item['price']) }}
                                                                    </span>
                                                                    <span style="font-size: 12px;">(税込)</span>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}"
                                                                    style="display: block;" target="_blank">
                                                                    <img alt="詳しくはこちら" height="42"
                                                                        src="{{ asset('/images/btn_small.png') }}"
                                                                        style="display: block; border: 0px; box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 5px;"
                                                                        width="165">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>

                                        @endforeach
                                        @if (count($category['products']) < 3 && ($key+1) == count($category['products']))
                                            {{-- Nếu không đủ 3 sản phẩm thì bổ sung thêm
                                            thẻ td rổng
                                            --}}
                                            @for ($addTD = 1; $addTD <= 3 - count($category['products']); $addTD++)
                                                <td valign="top" width="33.333333%"
                                                    style="padding-left: 10px; padding-right: 10px;">
                                                </td>
                                            @endfor
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach

        @endif
    </tbody>
</table>
{{--Slot data body ranking--}}
