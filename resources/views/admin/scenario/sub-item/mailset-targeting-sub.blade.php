<dl class="targeting-list">
    <dt class="">
        LTV
    </dt>
    <dd>
        <input type="text" id="form_ltv_min" class="size-num4" maxlength="20" name="ltv_from" v-model="data.ltv_from">
        ～
        <input type="text" id="form_ltv_max" class="size-num4" maxlength="20" name="ltv_to" v-model="data.ltv_to">
    </dd>
</dl>
<!-- ================ -->
<dl class="targeting-list">
    <dt class="">
        期間
    </dt>
    <dd class="purchase-period">
        <p class="description-sub-title">
            購入期間<span class="notes">※1年以内でご指定ください。</span>
        </p>
        <span class="select-date">
            <label for="form_purchase_period_min">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_purchase_period_min"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15" name="purchased_date_from"
                v-model="data.purchased_date_from">
        </span>
        ～
        <span class="select-date">
            <label for="form_purchase_period_max">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_purchase_period_max"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15" name="purchased_date_to"
                v-model="data.purchased_date_to">
        </span>
    </dd>
    <dd class="first-purchase-period">
        <p class="description-sub-title">初回購入期間
        </p>
        <span class="select-date">
            <label for="form_first_purchase_period_min">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_first_purchase_period_min"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15"
                name="first_time_purchased_date_from" v-model="data.first_time_purchased_date_from">
        </span>
        ～
        <span class="select-date">
            <label for="form_first_purchase_period_max">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_first_purchase_period_max"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15"
                name="first_time_purchased_date_to" v-model="data.first_time_purchased_date_from">
        </span>
    </dd>
    <dd class="last-purchase-period">
        <p class="description-sub-title">最終購入期間
        </p>
        <span class="select-date">
            <label for="form_last_purchase_period_min">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_last_purchase_period_min"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15"
                name="last_time_purchased_date_from" v-model="data.last_time_purchased_date_from">
        </span>
        ～
        <span class="select-date">
            <label for="form_last_purchase_period_max">
                <i class="far fa-calendar-alt"></i>
            </label>
            <input type="text" id="form_last_purchase_period_max"
                class="select-date datatype-datetime size-num4 hasDatepicker2" maxlength="15"
                name="last_time_purchased_date_to" v-model="data.last_time_purchased_date_from">
        </span>
    </dd>
</dl>
<!-- ================ -->
<dl class="targeting-list">
    <dt class="">
        購入回数
    </dt>
    <dd>
        <input type="text" id="form_buy_count_min" class="size-num2" maxlength="5" name="purchased_times_from"
            v-model="data.purchased_times_from">
        ～
        <input type="text" id="form_buy_count_max" class="size-num2" maxlength="5" name="purchased_times_to"
            v-model="data.purchased_times_to">
        回
    </dd>
</dl>
<!-- ================ -->
<dl class="targeting-list">
    <dt class="">
        売上累計
    </dt>
    <dd>
        <input type="text" id="form_order_total_min" class="size-num4" maxlength="11" name="cumulative_of_earnings_from"
            v-model="data.cumulative_of_earnings_from">
        ～
        <input type="text" id="form_order_total_max" class="size-num4" maxlength="1" name="cumulative_of_earnings_to"
            v-model="data.cumulative_of_earnings_from">
        円
    </dd>
</dl>
<!-- ================ -->
<dl class="targeting-list">
    <dt class="">
        年齢
    </dt>
    <dd>
        <input type="text" id="form_age_min" class="size-num2" maxlength="3" name="age_min" v-model="data.old_from">
        ～
        <input type="text" id="form_age_max" class="size-num2" maxlength="3" name="age_max" v-model="data.old_to">
        歳
    </dd>
</dl>
<!-- ================= -->
<dl class="targeting-list">
    <dt>性別</dt>
    <dd>
        <ul class="select-item select-all">
            <li>
                <input type="checkbox" id="form_sex_all" class="checkbox" name="sex_all"
                    v-on:change="handleCheckAll('sex')">
                <label for="form_sex_all">すべてにチェックを入れる/はずす</label>
            </li>
            <li>
                <input type="checkbox" value="1" class="checkbox select_sex" name="sex[]" id="form_sex_0"
                    v-model="data.sex">
                <label for="form_sex_0">男性</label>
            </li>
            <li>
                <input type="checkbox" value="2" class="checkbox select_sex" name="sex[]" id="form_sex_1"
                    v-model="data.sex">
                <label for="form_sex_1">女性</label>
            </li>
            <li>
                <input type="checkbox" value="3" class="checkbox select_sex" name="sex[]" id="form_sex_2"
                    v-model="data.sex">
                <label for="form_sex_2">不明</label>
            </li>
        </ul>
    </dd>
</dl>
<!-- ================= -->
<dl class="targeting-list">
    <dt class="">
        都道府県
    </dt>
    <dd>
        <ul class="select-item select-all">
            <li><input type="checkbox" id="form_pref_id_all" class="select" name="pref_id_all"
                    v-on:change="handleCheckAll('pref')">
                <label for="form_pref_id_all">すべてにチェックを入れる/はずす</label></li>
            <li class="pref-list-wrap">

                <!-- mẫu ban đầu chia các tỉnh thành tửng cùng -->
                {{-- <div class="pref-list clearfix">
                    <div class="pref-list-title">東北</div>
                    <div class="pref-list-data">
                        <p class="pref-list-data-select">
                            <input type="checkbox" value="2"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_2"
                                v-model="data.prefectures">
                            <label for="form_pref_id_2">青森県</label>
                        </p>
                        <p class="pref-list-data-select">
                            <input type="checkbox" value="3"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_3"
                                v-model="data.prefectures">
                            <label for="form_pref_id_3">岩手県</label>
                        </p>
                        <p class="pref-list-data-select"><input
                                type="checkbox" value="4"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_4"
                                v-model="data.prefectures"><label
                                for="form_pref_id_4">宮城県</label></p>
                        <p class="pref-list-data-select"><input
                                type="checkbox" value="5"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_5"
                                v-model="data.prefectures"><label
                                for="form_pref_id_5">秋田県</label></p>
                        <p class="pref-list-data-select"><input
                                type="checkbox" value="6"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_6"
                                v-model="data.prefectures"><label
                                for="form_pref_id_6">山形県</label></p>
                        <p class="pref-list-data-select"><input
                                type="checkbox" value="7"
                                class="select select_pref"
                                name="prefectures[]" id="form_pref_id_7"
                                v-model="data.prefectures"><label
                                for="form_pref_id_7">福島県</label></p>
                    </div>
                </div> --}}
                <!-- End mẫu ban đầu chia các tỉnh thành tửng cùng -->
                <div class="pref-list clearfix">
                    <div class="pref-list-data">
                        @foreach ($prefectures as $key => $item)
                        <p class="pref-list-data-select">
                            <input type="checkbox" value="{{$item->prefecture}}" class="select select_pref"
                                name="prefectures[]" id="form_pref_id_{{$key+1}}" v-model="data.prefectures">
                            <label for="form_pref_id_{{$key+1}}">{{$item->prefecture}}</label>
                        </p>
                        @endforeach
                    </div>
                </div>

                <div class="pref-list clearfix">
                    {{-- <div class="pref-list-title"></div> --}}
                    <div class="pref-list-data">
                        <p class="pref-list-data-select">
                            <input type="checkbox" value="" class="select select_pref" name="prefectures[]"
                                id="form_pref_id_0" v-model="data.prefectures">
                            <label for="form_pref_id_0">不明</label>
                        </p>
                    </div>
                </div>
            </li>
            <span class="help-inline"></span>
        </ul>
    </dd>
</dl>
<!-- ================= -->
<dl class="targeting-list">
    <dt>購入商品<span class="title-supple">（商品は5件まで選択可能です）</span></dt>
    <dd>
        <div class="range-select">
            <input type="radio" value="1" class="" name="is_all_or_one_purchase" id="form_product_and_or_0"
                v-model="data.is_all_or_one_purchase">
            <label for="form_product_and_or_0">すべての商品を含む</label>
            <input type="radio" value="2" class="" name="is_all_or_one_purchase" checked="checked"
                id="form_product_and_or_1" v-model="data.is_all_or_one_purchase">
            <label for="form_product_and_or_1">いずれかの商品を含む</label>
        </div>
        <ul class="setting-form item-select" id="item-select">
        </ul>

        <input type="hidden" id="form_created_at" name="created_at" value="">

        <div class="btn-box" id="item_select_btn" style="display:block;">
            <a class="btn btn-blue size-small btn_select_product" v-on:click.prevent="handleOpenPopup(1)">商品を選択</a>
        </div>
        <div class="btn-box" id="item_select_coution" style="display:none">
            <span style="color:red;">これ以上商品は選択することができません</span>
        </div>
    </dd>
</dl>
