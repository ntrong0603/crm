<div class="modal fade" id="send-mail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body review-template-tab">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h2>テスト送信<span id="js-select-tab-text" style="font-size: 1rem; margin-left: 40px;"></span></h2>

            <form id="form_test_mail" accept-charset="utf-8" method="post" @submit.prevent="onSubmitTestSend">
                <div class="testing-contents-wrap">
                    <!-- テストメール送信先 -->
                    <dl>
                        <dt>
                            <label placeholder="test_send_to" for="form_test_send_to">テスト送信先メールアドレス<span
                                    class="required-icon">必須</span></label>
                        </dt>
                        <dd>
                            <input type="text" required="required" id="form_test_send_to" name="test_send_to"
                                value="" v-model="dataSave.test_send_to">
                            <p class="remodal-text">※アドレスはカンマ(,)区切りで5件まで指定可能です。</p>
                            <span class="help-inline"></span>
                        </dd>
                    </dl>
                    <!-- テストメール用メモ -->
                    <dl>
                        <dt>
                            <label placeholder="test_memo" for="form_test_memo">テスト用メモ</label>
                        </dt>
                        <dd>
                            <textarea rows="3" id="form_test_memo" name="test_memo"
                                v-model="dataSave.test_memo"></textarea>
                            <p class="remodal-text">※メールの一番上に表示されます。</p>
                            <span class="help-inline"></span>
                        </dd>
                    </dl>

                    <dl>
                        <dt>
                            <label placeholder="test_send_to" for="form_test_send_to">メール形式<span
                                    class="required-icon">必須</span></label>
                        </dt>
                        <dd>
                            <div class="testing-modal-wrap-radio">
                                <input type="radio" value="1" required="required" id="form_send_type_0"
                                    class="form_body_type" name="send_type" v-model="dataSave.send_type">
                                <label for="form_send_type_0">HTMLメール/テキストメール</label>&nbsp;

                                <!-- <input type="radio" value="2" required="required" id="form_send_type_1"
                                    class="form_body_type" name="send_type" v-model="dataSave.send_type">
                                <label for="form_send_type_1">テキストメールのみ</label>&nbsp; -->

                                <span class="help-inline"></span>
                            </div>
                        </dd>
                    </dl>

                    <div class="testing-btn-area">
                        <a class="btn btn-default btn-gray" data-dismiss="modal" aria-label="Close"
                            id="js-test-mail-close-btn">キャンセル</a>
                        <button class="btn btn-default btn-add" id="js-send-test-mail-btn"
                            type="submit">テスト送信</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
</div>