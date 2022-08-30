<div class="mailset-rule">

    <div class="schedule-btn">
        <a id="btn_status" class="timeline-btn btn_stop_or_play_sch" data-toggle="tooltip" title="適用中"
            href="javascript:void(0)"><i class="fas fa-play"></i></a>
        <a id="btn_status_none" class="timeline-btn btn_stop_or_play_sch" data-toggle="tooltip" title="非適用中"
            style="display:none" href="javascript:void(0)"><i class="fas fa-pause"></i></a>
    </div>
    <h4 class="rule-title">配信停止ルール<span class="title-supple">（1通目以降のスケジュールに適用）</span></h4>

    <div class="rule-item-wrap" id="rule-item-wrap">
        <input type="hidden" id="form_send_rule_status" class="send_rule_status" name="send_rule_status" value="">
        <div class="rule-switch">
            <p class="rule-text">下記の
                <select id="form_send_rule_product_eq_neq" class="select-rule-item" name="is_buyed"
                    v-model="data.is_buyed">
                    <option value="1">商品が購入</option>
                    <option value="2">商品以外が購入</option>
                </select>
                されたら、配信停止
            </p>
        </div>


        <div class="range-select">
            <input type="radio" value="1" class="" name="is_all_or_one_stop" id="form_send_rule_product_and_or_0"
                v-model="data.is_all_or_one_stop">
            <label for="form_send_rule_product_and_or_0">すべての商品を含む</label>
            <input type="radio" value="2" class="" name="is_all_or_one_stop" checked="checked"
                id="form_send_rule_product_and_or_1" v-model="data.is_all_or_one_stop">
            <label for="form_send_rule_product_and_or_1">いずれかの商品を含む</label>
        </div>
        <ul class="setting-form item-select" id="item-rule-select">
        </ul>

        <div class="btn-box" id="item_rule_select_btn" style="display:block;">
            <a class="btn btn-blue size-small" v-on:click.prevent="handleOpenPopup(2)">停止条件商品を選択</a>
            <br><br>
            ※商品は30件まで選択可能です</div>
    </div>

    <div class="btn-box" id="item_rule_select_coution" style="display:none">
        <span style="color:red;">これ以上商品は選択することができません</span>
    </div>
</div>
