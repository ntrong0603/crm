<div class="mail-edit-info mail-content" id="intro_step2">
    <dl>
        <dt><label for="mail-send-name">送信者名</label></dt>
        <dd>
            <input type="text" value="早崎誠悟" id="mail-send-name" class="size-large" name="sender_name"
                v-model="dataSave.mail_from_name">
            <span class="help-inline"></span>
        </dd>
    </dl>
    <dl>
        <dt>
            <label placeholder="mail_from" for="mail_from">送信者メールアドレス<span class="required-icon">必須</span></label>
        </dt>
        <dd v-bind:class="errors.mail_from ? 'has-error' : ''">
            <input type="text" value="" id="mail_from" class="size-large" name="mail_from" v-model="dataSave.mail_from">
            <span class="help-inline" v-for="error of errors.mail_from">
                @{{error}}
            </span>
        </dd>
    </dl>
    {{-- <dl>
        <dt><label placeholder="body_type" for="mail_template_option">メール形式<span class="required-icon">必須</span></label>
        </dt>
        <dd v-bind:class="errors.mail_template_option ? 'has-error' : ''">
            <ul class="select-list">
                <input type="radio" value="0" id="mail_template_option_0" class="mail_template_option"
                    name="mail_template_option" v-model="dataSave.mail_template_option">
                <label for="mail_template_option_0">HTMLメール/テキストメール</label>
                <input type="radio" value="1" id="mail_template_option_1" class="mail_template_option"
                    name="mail_template_option" v-model="dataSave.mail_template_option">
                <label for="mail_template_option_1">テキストメールのみ</label>
            </ul>
            <p>※テキストメール形式で開封された場合、効果測定はできません。</p>
            <span class="help-inline" v-for="error of errors.mail_template_option">
                @{{error}}
            </span>
        </dd>
    </dl> --}}
    {{-- <dl>
        <dt><label placeholder="optout" for="form_optout">配信停止用リンク<span class="required-icon">必須</span></label></dt>
        <dd>
            <ul class="select-list">
                <input type="radio" value="1" id="link_unsubscribe_1" class="link_unsubscribe" name="link_unsubscribe"
                    v-model="dataSave.link_unsubscribe">
                <label for="link_unsubscribe_1">挿入する</label>
                <input type="radio" value="0" id="link_unsubscribe_0" class="link_unsubscribe" name="link_unsubscribe"
                    v-model="dataSave.link_unsubscribe">
                <label for="link_unsubscribe_0">挿入しない</label>
            </ul>
            <span class="help-inline" v-for="error of errors.link_unsubscribe">
                @{{error}}
            </span>
        </dd>
    </dl> --}}
</div>
