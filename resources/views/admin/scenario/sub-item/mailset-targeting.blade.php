<div class="mailset-section mailset-halft-section mailset-targeting">
    <h3>ターゲティング設定</h3>
    <div class="mailset-box">
        <dl class="targeting-list">
            <dt class="">
                受信可否属性
            </dt>
            <dd>
                <p class="msg-error" id="allow_mail_error" style="display: none">
                    <i class="fas fa-exclamation-triangle"></i>
                    最低1つ以上選択してください
                </p>
                <ul class="select-item select-row">
                    <li>
                        <input type="checkbox" value="1" class="checkbox select_allow_mail" name="receive_property[1]"
                            id="form_allow_mail_1" v-model="data.receive_property">
                        <label for="form_allow_mail_1">受信許可している顧客</label>
                    </li>
                    <li>
                        <input type="checkbox" value="0" class="checkbox select_allow_mail" name="receive_property[0]"
                            id="form_allow_mail_0" v-model="data.receive_property">
                        <label for="form_allow_mail_0">受信拒否している顧客</label>
                    </li>
                </ul>
                <p class="notes">※シナリオメールは、広告的要素が含まれない内容を想定しています。</p>
            </dd>
        </dl>
        <!-- ================= -->
        <dl class="targeting-list">
            <dt class="">
                対象顧客
            </dt>
            <dd>
                <ul class="select-item select-all">
                    <li>
                        <input type="checkbox" id="form_crm_condition_sel_all" name="crm_condition_sel_all"
                            v-on:change="handleCheckAll('crm_condition')">
                        <label for="form_crm_condition_sel_all">すべてにチェックを入れる/はずす</label>
                    </li>
                    <li>
                        <input type="checkbox" value="1" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_0"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_0">現役新規</label>
                    </li>
                    <li>
                        <input type="checkbox" value="2" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_1"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_1">現役入門</label>
                    </li>
                    <li>
                        <input type="checkbox" value="3" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_2"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_2">現役安定</label>
                    </li>
                    <li>
                        <input type="checkbox" value="4" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_3"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_3">現役流行</label>
                    </li>
                    <li>
                        <input type="checkbox" value="5" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_4"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_4">現役優良</label>
                    </li>
                    <li>
                        <input type="checkbox" value="6" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_5"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_5">離脱新規</label>
                    </li>
                    <li>
                        <input type="checkbox" value="7" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_6"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_6">離脱入門</label>
                    </li>
                    <li>
                        <input type="checkbox" value="8" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_7"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_7">離脱安定</label>
                    </li>
                    <li>
                        <input type="checkbox" value="9" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_8"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_8">離脱流行</label>
                    </li>
                    <li>
                        <input type="checkbox" value="10" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_9"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_9">離脱優良</label>
                    </li>
                    <li>
                        <input type="checkbox" value="0" crm_condition_sel="crm_condition_sel"
                            class="checkbox select_condition" name="customer_target[]" id="form_crm_condition_sel_10"
                            v-model="data.customer_target">
                        <label for="form_crm_condition_sel_10">未購入</label>
                    </li>
                    <span class="help-inline"></span>
                </ul>
            </dd>
        </dl>
        <!-- ================ -->
        {{-- include mail targeting sub --}}
    </div>
</div>
