"use strict";
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
// Register event global for libs in vue
axios.defaults.headers.post["X-Requested-With"] = "XMLHttpRequest";
const loading = $(".loading");
//Xử lý popup notify
function msgCustom(type = "success", msg, timer = 1500, position = "center") {
    //Khởi tạo popup notify
    let Toast = Swal.mixin({
        toast: true,
        position: "center-center",
        showConfirmButton: false,
        timer: 1500,
    });

    Toast.fire({
        icon: type,
        title: msg,
        showConfirmButton: false,
        timer: timer,
        position: position,
    });
}

//Sử lý alert conform action
function alertAction(
    title = "",
    note = "",
    callBack,
    desComform = "Yes, Remove now!"
) {
    Swal.fire({
        title: "Are you sure " + title + "?",
        html: '<span class="alert_action">' + note + "</span>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: desComform,
        onClose: () => {},
    })
        .then((result) => {
            if (result.value) {
                callBack();
            }
        })
        .catch((error) => {
            console.log(error);
            loading.hide();
        });
}

//chuyển chuổi số thành số ngày
function convertIntToDay(intDay) {
    return Math.abs(intDay / 1000 / 60 / 60 / 24);
}

//Xử lý active menu
function activeMenu() {
    if (typeof routeName == "undefined") {
        return;
    }
    let menu = $("#js-main-menu");
    let elActive = menu.find("a[data-route = '" + routeName + "']");
    elActive.addClass("active");
    elActive.attr("display", "block");
    let elUl = elActive.parent().parent();
    if (elUl.hasClass("nav-treeview")) {
        let elLiMain = elUl.parent();
        elLiMain.addClass("menu-open");
        elLiMain.find(">a").addClass("active");
    }
}

// Xử lý reponsive table
function activeTabelResponsive() {
    let firstColumn = $(".first-column");
    firstColumn.bind("click", null);
    firstColumn.on("click", function () {
        let parent = $(this).parent();
        if (parent.hasClass("active")) {
            parent.removeClass("active");
        } else {
            parent.addClass("active");
        }
    });
}

/** caculate date and format
 * @param string dateFormat min date
 * @param int value about time (day)
 * @param boolean formatDate format Y-m or Ym
 * @return string
 */
function formatDate(dateFormat, value, formatDate = true) {
    let fDate = new Date(dateFormat);
    fDate.setMonth(fDate.getMonth() + value);
    if (formatDate) {
        return (
            fDate.getFullYear() + "-" + ("0" + (fDate.getMonth() + 1)).slice(-2)
        );
    } else {
        return fDate.getFullYear() + ("0" + (fDate.getMonth() + 1)).slice(-2);
    }
}
/**
 * Format number
 * @param float number
 * @param int digits minimum fraction digits
 * @return string
 */
function formatNumber(number, digits = 0) {
    if (typeof number == "undefined" || number == null) {
        return 0;
    }
    let num = parseFloat(number).toFixed(digits);
    let formatter = new Intl.NumberFormat("ja-JP", {
        minimumFractionDigits: digits,
    });
    return formatter.format(num);
}
activeMenu();

/**
 * Default option datepicker
 */
const optionDatepicker = {
    showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
    closeText: "閉じる", // set a close button text
    currentText: "今日", // set today text
    monthNames: [
        "1月",
        "2月",
        "3月",
        "4月",
        "5月",
        "6月",
        "7月",
        "8月",
        "9月",
        "10月",
        "11月",
        "12月",
    ], // set month names
    monthNamesShort: [
        "1月",
        "2月",
        "3月",
        "4月",
        "5月",
        "6月",
        "7月",
        "8月",
        "9月",
        "10月",
        "11月",
        "12月",
    ], // set short month names
    // dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'], // set days names
    // dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], // set short day names
    dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"], // set more short days names
    dateFormat: "yy-mm-dd", // set format date
    showAnim: "slideDown",
    beforeShow: function (input) {
        setTimeout(function () {
            var buttonPane = $(input)
                .datepicker("widget")
                .find(".ui-datepicker-buttonpane");
            var btn = $(
                '<button class="ui-datepicker-current' +
                    ' ui-state-default ui-priority-secondary ui-corner-all"' +
                    ' type="button">Clear</button>'
            );
            btn.unbind("click").bind("click", function () {
                $.datepicker._clearDate(input);
            });
            btn.appendTo(buttonPane);
        }, 1);
    },
};
//Function event click button today in calendar
$.datepicker._gotoToday = function (id) {
    var target = $(id);
    var inst = this._getInst(target[0]);
    if (this._get(inst, "gotoCurrent") && inst.currentDay) {
        inst.selectedDay = inst.currentDay;
        inst.drawMonth = inst.selectedMonth = inst.currentMonth;
        inst.drawYear = inst.selectedYear = inst.currentYear;
    } else {
        var date = new Date();
        inst.selectedDay = date.getDate();
        inst.drawMonth = inst.selectedMonth = date.getMonth();
        inst.drawYear = inst.selectedYear = date.getFullYear();
        // the below two lines are new
        this._setDateDatepicker(target, date);
        this._selectDate(id, this._getDateDatepicker(target));
    }
    this._notifyChange(inst);
    this._adjustDate(target);
};
$.datepicker.setDefaults(optionDatepicker);

// get Param all for url
function getUrlParamAll() {
    let vars = {};
    window.location.href.replace(
        /[?&]+([^=&]+)=([^&]*)/gi,
        function (m, key, value) {
            vars[key] = value;
        }
    );
    return vars;
}
// handle set size view
const viewport = document.getElementById("viewport");

var viewportSet = function () {
    setTimeout(function () {
        if (screen.width >= 768 && screen.width <= 1200) {
            viewport.setAttribute("content", "width=1200, user-scalable=0");
        } else {
            viewport.setAttribute(
                "content",
                "width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0"
            );
        }
    }, 200);
};

viewportSet();

window.onload = function () {
    viewportSet();
};

window.onresize = function () {
    viewportSet();
};

//function clear data variable
function clearVariable(obj = "") {
    let clear = "";
    if (obj != "") {
        if (typeof obj === "object") {
            for (var variableKey in obj) {
                if (obj.hasOwnProperty(variableKey)) {
                    // if (typeof obj[variableKey] === "object") {
                    // clearVariable(obj[variableKey]);
                    // }
                    delete obj[variableKey];
                }
            }
            if (Array.isArray(obj)) {
                clear = [];
            } else {
                clear = {};
            }
        }
    }
    return clear;
}

//function copy object
function copyObj(obj = "") {
    if (obj != "") {
        return JSON.parse(JSON.stringify(obj));
    }
    return {};
}

/**
 * Function get data width axios
 * @param string url get data
 * @param object param get data
 * @return promise
 */
async function getData(url, param = {}) {
    let data = await axios
        .get(url, { params: param })
        .then((response) => response)
        .catch((error) => error);
    return data;
}

/**
 * Function post data width axios
 * @param string url post data
 * @param object param post data
 * @return promise
 */
async function postData(url, param = {}) {
    let data = await axios
        .post(url, param)
        .then((response) => response)
        .catch((error) => error);
    return data;
}
