$(document).ready(function() {
    // kiem tra form co class auth
    // $("form.auth").submit(function(event) {
    //     var inputs = $(this).find('input');
    //     var check = valid(inputs);
    //     return check;
    // });
});
/**
 * Process check value input
 * @param {array element input} inputs
 * @return boolean
 */
function valid(inputs) {
    var errors = false;
    var reg_mail = /^[A-Za-z0-9]+([_\.\-]?[A-Za-z0-9])*@[A-Za-z0-9]+([\.\-]?[A-Za-z0-9]+)*(\.[A-Za-z]+)+$/;
    var input_length = inputs.length;
    for (var i = 0; i < input_length; i++) {
        var value = inputs[i].value;
        var id = inputs[i].getAttribute('id');

        // Tạo phần tử span lưu thông tin lỗi
        var span = document.createElement('span');
        span.className = 'custom-err';
        // Nếu span đã tồn tại thì remove
        var p = inputs[i].parentNode;
        if (p.lastChild.nodeName == 'SPAN') {
            p.removeChild(p.lastChild);
            //$(inputs[i]).css( "border-color", "" );
        }
        // Kiểm tra rỗng
        if (value == '') {
            span.innerHTML = 'Yêu cầu nhập thông tin';
        } else {
            // Kiểm tra các trường hợp khác
            if (id == 'email') {
                if (reg_mail.test(value) == false) {
                    span.innerHTML = 'Email: example@email.com';
                }
            }
            // Kiểm tra password
            if (id == 'password') {
                var length = value.length
                if (length < 6) {
                    span.innerHTML = 'Mật khẩu ít nhất 6 ký tự, bao gồm cả số và chữ';
                }
            }
        }
        // Nếu có lỗi thì chèn span vào hồ sơ, chạy onchange, submit return false, highlight border
        if (span.innerHTML != '') {
            try {
                //$(inputs[i]).css( "border-color", "red" );
                inputs[i].parentNode.appendChild(span);
                errors = true;
            } catch (err) {
                console.log(err);
                return false;
            }
        }
    } // end for
    return !errors;
} // end valid()