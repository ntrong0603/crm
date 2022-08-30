{{--Slot data body review--}}
@php
$mall = $mall ?? 'biz';
$param = $param ?? '';
@endphp
@if (!empty($dataProduct))
    <form action="{{ route('review-product', ['mall' => $mall]) }}" method="post" name="contact_form" style="width: 100%;">
        <input type="hidden" name="token" value="{{ $token }}">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                @php
                $count = 0;
                @endphp
                @foreach ($dataProduct as $key => $item)
                    <tr>
                        <td style="padding: 0px 0px 22px 7px; font-size: 20px;">
                            <b>お客様のご意見は当店にとって重要です！</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px 0px 20px 7px;">
                            <input name="product_id[]" type="hidden" value="{{ $item['product_id'] }}">
                            <input name="model_{{ $item['product_id'] }}" type="hidden" value="{{ $item['model'] }}">
                            <input name="product_name_{{ $item['product_id'] }}" type="hidden" value="{{ $item['name'] }}">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td style="padding-top: 15px;" valign="top" width="200">
                                            <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}"
                                                rel="noopener" target="_blank">
                                                <img alt="{{ $item['name'] }}" height="180" src="{{ $item['image'] }}"
                                                    style="display: block; margin: auto;" width="180">
                                            </a>
                                            <h2
                                                style="color: rgb(0, 0, 255); font-size: 11px; font-weight: 700; line-height: 18px; margin: 0px; padding: 8px 0px 0px;">
                                                <a rel="nofollow" href="{{ crm_url_product(mb_strtolower($item['model']), $mall, $param) }}" rel="noopener" style="display: inline-block;"
                                                    target="_blank">{{ $item['name'] }}
                                                </a>
                                            </h2>
                                        </td>
                                        <td valign="top" width="10">
                                            &nbsp;</td>
                                        <td valign="top" width="305">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size: 14px; padding: 0px 0px 8px;">
                                                            最初にこの商品を5段階で評価してください
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 4px 0px 12px;">
                                                            <ul
                                                                style="display: block; list-style-type: none; padding: 0px; margin: 0px; font-size: 13px; vertical-align: middle;">
                                                                <li style="display: inline-block; margin: 0px;">
                                                                    <span
                                                                        style="position: relative; top: -0.1em;">悪い</span>
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <input name="rating_{{ $item['product_id'] }}"
                                                                        type="radio" value="1">
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <input name="rating_{{ $item['product_id'] }}"
                                                                        type="radio" value="2">
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <input name="rating_{{ $item['product_id'] }}"
                                                                        type="radio" value="3">
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <input name="rating_{{ $item['product_id'] }}"
                                                                        type="radio" value="4">
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <input checked="checked"
                                                                        name="rating_{{ $item['product_id'] }}"
                                                                        type="radio" value="5">
                                                                </li>
                                                                <li style="display: inline-block;">
                                                                    <span
                                                                        style="position: relative; top: -0.1em;">良い</span>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size: 14px;">
                                                            お名前</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 0px 8px;">
                                                            <input name="customer_name_{{ $item['product_id'] }}"
                                                                placeholder="こちらに入力されたお名前が表示されます"
                                                                style="width: 100%; border-radius: 2px; border: 1px solid rgb(112, 112, 112); padding: 4px; box-sizing: border-box; font-size: 12px; line-height: 1.2; background-color: rgb(255, 255, 255);"
                                                                type="text" required>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size: 14px;">
                                                            レビュー内容</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 0px 10px;">
                                                            <textarea name="customer_note_{{ $item['product_id'] }}"
                                                                placeholder="こちらにお客様のご意見を入力してください"
                                                                style="width: 100%; height: 70px; border-radius: 2px; border: 1px solid rgb(112, 112, 112); padding: 4px; box-sizing: border-box; font-size: 12px; line-height: 1.2; background-color: rgb(255, 255, 255);"
                                                                required></textarea>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    {{-- line --}}
                    @php
                    $count++;
                    @endphp
                    @if ($count < count($dataProduct))
                        <tr>
                            <td style="padding: 0px 0px 20px 7px;">
                                <hr
                                    style="width: 100%; height: 1px; border: 0px; background-color: rgb(173, 173, 173);">
                            </td>
                        </tr>
                    @endif
                    {{-- line --}}
                @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">
            <button style="border: none; background: none;" type="submit">
                <img alt="btn-submit" src="{{ asset('images/btn_review.png') }}">
            </button>
        </div>
    </form>
@endif
{{--Slot data body review--}}
