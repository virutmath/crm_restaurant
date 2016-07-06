var i = 0;
var trigger_keyup = '';
var IntlMixin = ReactIntl.IntlMixin;
var FormattedNumber = ReactIntl.FormattedNumber;
function MenuItem(data) {
    var _default = {
        men_id: 0,
        men_name: '',
        men_price: 0,
        men_price1: 0,
        men_price2: 0,
        men_unit: 0,
        men_image: '',
        men_editable: false
    };
    $.extend(_default, data);
    this.men_id = _default.men_id;
    this.men_name = _default.men_name;
    this.men_price = parseFloat(_default.men_price);
    this.men_price1 = parseFloat(_default.men_price1);
    this.men_price2 = parseFloat(_default.men_price2);
    this.men_unit = _default.men_unit;
    this.men_image = _default.men_image;
    this.men_editable = _default.men_editable;
    return this;
}
function BillInfo(data) {
    var _default = {
        customerDiscount: 0,
        customerID: null,
        customerCode: null,
        customerName: '',
        debit: false,
        debitMoney: 0,
        debitTime: null,
        extraFee: 0,
        note: '',
        payType: 0,
        startTime: Date.now(),
        startTimeStr: '',
        VAT: 0,
        staffCode: null,
        staffID: 0,
        staffName: '',
        totalMoney: 0,
        finalMoney: 0
    };
    $.extend(_default, data);
    this.customerDiscount = _default.customerDiscount;
    this.customerID = _default.customerID;
    this.customerCode = _default.customerCode;
    this.customerName = _default.customerName;
    this.debit = _default.debit;
    this.debitMoney = _default.debitMoney;
    this.debitTime = _default.debitTime;
    this.extraFee = _default.extraFee;
    this.note = _default.note;
    this.payType = _default.payType;
    this.startTime = _default.startTime;
    this.startTimeStr = _default.startTimeStr;
    this.VAT = _default.VAT;
    this.staffCode = _default.staffCode;
    this.staffID = _default.staffID;
    this.staffName = _default.staffName;
    this.totalMoney = _default.totalMoney;
    this.finalMoney = _default.finalMoney;
    return this;
}
//inherit from MenuItem
function MenuInDesk(data) {
    MenuItem.call(this, data);
    var _default = {
        cdm_price: 0,
        cdm_price_type: 'men_price',
        cdm_number: 0,
        cdm_desk_id: 0,
        cdm_menu_discount: 0
    };
    $.extend(_default, data);
    this.cdm_price = parseFloat(_default.cdm_price);
    this.cdm_price_type = _default.cdm_price_type;
    this.cdm_number = parseFloat(_default.cdm_number);
    this.cdm_desk_id = _default.cdm_desk_id;
    this.cdm_menu_discount = parseFloat(_default.cdm_menu_discount);
    return this;
}
MenuInDesk.prototype = new MenuItem();
MenuInDesk.prototype.constructor = MenuInDesk;

function DeskItem(data) {
    var _default = {
        des_id: 0,
        des_name: '',
        full_name: '',
        is_active: false
    };
    $.extend(_default, data);
    this.des_id = _default.des_id;
    this.des_name = _default.des_name;
    this.full_name = _default.full_name;
    this.is_active = _default.is_active;
    return this;
}

var HomeScript = {
    flagSetDebit: false,
    ajaxExtendUrl: {
        loadModalSelectCustomer: '/admin/core/customers/index_modal.php',
        loadModalSelectStaff: '/admin/core/users/index_modal.php',
        loadMindowListDesk: '/admin/core/desks/index.php'
    },
    domElement: {
        mainSale: $('#main-sale'),
        sectionContent: $('.section-content'),
        centerListing: $('#center-listing'),
        listingMenu: $('.list-menu'),
        listingDesk: $('.list-desk-bound'),
        currentDeskName: $('#current_desk_name'),
        startTimeString: $('#start_time_string'),
        billNote: $('#cud_note'),
        extraFee: $('#cud_extra_fee'),
        extraFeeText: $('#extra_fee_text'),
        vat: $('#cud_vat'),
        vatExt: $('#vat-ext'),
        customerCash: $('#cud_customer_cash'),
        customerCashText: $('#customer_cash_text'),
        customerDiscount: $('#cud_customer_discount'),
        customerDiscountText: $('#discount-text'),
        customerCode: $('#sale_customer_code'),
        searchCustomer: $('#search_customer'),
        staffCode: $('#sale_staff_code'),
        searchStaff: $('#search_staff'),
        menuNumber: $('#cdm_number'),
        menuDiscount: $('#cdm_menu_discount'),
        menuPrice: $('#men_price'),
        menuPrice1: $('#men_price1'),
        menuPrice2: $('#men_price2'),
        menuName: $('#men_name'),
        menuImage: $('#men_image'),
        totalMoney: $('#total-money'),
        finalMoney: $('#final-money'),
        debitCheckbox: $('#is-debit'),
        searchMenuText: $('#search-menu-text')
    },
    react: {
        tableMenu: {}
    },
    listMenu: [],
    listDesk: [],
    currentMenu: {
        domElement: null,
        menuItem: new MenuInDesk()
    },
    currentDesk: {
        deskItem: new DeskItem(),
        domElement: null,
        menuList: [],
        billInfo: new BillInfo()
    },
    infoRestaurant: {},
    beforeData: {
        currentDesk: {},
        currentMenu: {}
    }
};
//build danh sức thực đơn được lựa chọn
var MenuList = React.createClass({displayName: "MenuList",
    render: function () {
        var props = this.props;
        var rowLi = [];
        for (var i in props.list_category_menu) {
            var data = props.list_category_menu[i];
            rowLi.push(React.createElement(MenuList.CategoryItem, {
                cat_id: data.cat_id, 
                cat_name: data.cat_name, 
                count_menu: data.count_menu, 
                list_cat_child: data.list_cat_child}
                ))
        }
        return React.createElement("ul", {className: "list-unstyled list-menu"}, 
            rowLi
        )
    }
});
MenuList.CategoryItem = React.createClass({displayName: "CategoryItem",
    render: function () {
        var props = this.props;
        var onClickFn = function () {
            HomeScript.view.collapse(props.cat_id);
        };
        var rowCatChild = [];
        for (var i in props.list_cat_child) {
            var cat_child = props.list_cat_child[i];
            rowCatChild.push(React.createElement(MenuList.CategoryItemChild, {
                cat_id: cat_child.cat_id, 
                cat_name: cat_child.cat_name, 
                count_menu: cat_child.count_menu, 
                list_menu_child: cat_child.list_menu_child}
                ));
        }
        return React.createElement("li", {className: "list-item item-cat-parent"}, 
            React.createElement("label", {className: "item-name", onClick: onClickFn}, 
                React.createElement("i", {className: "fa fa-minus-square-o"}), " ", 
                React.createElement("span", null, props.cat_name, " (", props.count_menu, ")")
            ), 
            React.createElement("ul", {className: "list-cat-child list-unstyled", "data-collapse-id": props.cat_id}, 
                rowCatChild
            )
        )
    }
});
MenuList.CategoryItemChild = React.createClass({displayName: "CategoryItemChild",
    render: function () {
        var props = this.props;
        var onClickFn = function () {
            HomeScript.view.collapse(props.cat_id)
        };

        var rowMenu = [];
        for (var i in props.list_menu_child) {
            rowMenu.push(React.createElement(MenuList.MenuItem, {
                    men_id: props.list_menu_child[i].men_id, 
                    men_name: props.list_menu_child[i].men_name}
                ));
        }
        return React.createElement("li", {className: "list-item item-cat-child"}, 
            React.createElement("label", {className: "item-name", onClick: onClickFn}, 
                React.createElement("i", {className: "fa fa-caret-down"}), " ", 
                props.cat_name, " (", props.count_menu, ")"
            ), 
            React.createElement("ul", {className: "list-menu-child list-unstyled", "data-collapse-id": props.cat_id}, 
                rowMenu
            )
        )
    }
});
MenuList.MenuItem = React.createClass({displayName: "MenuItem",
    render : function () {
        var props = this.props;
        var onDbClick = function () {
            HomeScript.addMenuToDesk(props.men_id);
        };
        return React.createElement("li", {className: "list-item item-menu", "data-id": props.men_id, 
                   "data-name": props.men_name}, 
                    React.createElement("label", {className: "item-name", onDoubleClick: onDbClick}, 
                        "- ", props.men_name
                    )
                )
    }
});
//build danh sách thực đơn ở bàn được chọn từ currentDesk
var TableHead = React.createClass({displayName: "TableHead",
    render: function () {
        return React.createElement("thead", null, 
        React.createElement("tr", null, 
            React.createElement("th", {width: "32px;"}, "STT"), 
            React.createElement("th", {width: "40%"}, 
                React.createElement("strong", null, "Tên thực đơn")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "ĐVT")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "SL")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Đơn giá")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Giảm")
            ), 
            React.createElement("th", null, 
                React.createElement("strong", null, "Thành tiền")
            )
        )
        )
    }
});
var TableRow = React.createClass({displayName: "TableRow",
    mixins: [IntlMixin],
    render: function () {
        var element_id = 'record_' + this.props.id;
        var id = this.props.id;
        var onClickFn = function () {
            HomeScript.selectMenuInDesk(id);
        };
        return React.createElement("tbody", null, 
        React.createElement("tr", {id: element_id, "data-id": id, className: "menu-desk-menu record-item", onClick: onClickFn}, 
            React.createElement("td", {className: "center"}, this.props.stt), 
            React.createElement("td", null, this.props.name), 
            React.createElement("td", {className: "center"}, this.props.unit), 
            React.createElement("td", {className: "center"}, React.createElement(FormattedNumber, {value: this.props.number})), 
            React.createElement("td", {className: "text-right"}, React.createElement(FormattedNumber, {value: this.props.price, style: "currency", currency: "VND"})), 
            React.createElement("td", {className: "center"}, React.createElement(FormattedNumber, {value: this.props.discount/100, style: "percent"})), 
            React.createElement("td", {className: "text-right"}, React.createElement(FormattedNumber, {value: this.props.total, style: "currency", currency: "VND"}))
        )
        )
    }
});
HomeScript.react.TableMenu = React.createClass({displayName: "TableMenu",
    render: function () {
        var rowsMenu = [];
        for (var i in HomeScript.currentDesk.menuList) {
            i = parseInt(i);
            var menuItem = HomeScript.currentDesk.menuList[i];
            rowsMenu.push(React.createElement(TableRow, {
                stt: ++i, 
                id: menuItem.men_id, 
                name: menuItem.men_name, 
                unit: menuItem.men_unit, 
                number: menuItem.cdm_number, 
                price: menuItem.men_price, 
                discount: menuItem.cdm_menu_discount, 
                total: HomeScript.cashMenuItem(menuItem)}
                ));
        }
        return (
            React.createElement("table", {className: "table table-bordered table-hover table-listing", id: "table-listing"}, 
                React.createElement(TableHead, null), 
                rowsMenu, 
                React.createElement("tbody", null, 
                React.createElement("tr", {className: "footer"}, 
                    React.createElement("td", {colSpan: "9"}, 
                        React.createElement("span", {class: "fl nowrap"}, "Tổng cộng ", HomeScript.currentDesk.menuList.length, " món")
                    )
                )
                )
            )
        )
    }
});
HomeScript.addMenuToDesk = function (menu_id) {
    if (!HomeScript.currentDesk.domElement) {
        bootbox.alert('Không thể thêm thực đơn! Chọn một bàn bắt đầu để thêm');
        return false;
    }
    if (!HomeScript.currentDesk.domElement.hasClass('active')) {
        bootbox.alert('Bàn chưa được mở, cần mở bàn để bắt đầu thêm thực đơn');
        return false;
    }

    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'addMenuToDesk', desk_id: HomeScript.currentDesk.deskItem.des_id, menu_id: menu_id},
        dataType: 'json',
        success: function (resp) {
            loadingProgress('hide');
            if (resp.array_menu) {
                HomeScript.parseResponseAddMenuToDesk(resp);
                //tính tiền
                HomeScript.cashCurrentBill();
                HomeScript.view.buildCurrentDesk();
                HomeScript.selectMenuInDesk(menu_id);
            }
        },
        beforeSend: function () {
            loadingProgress('show');
        }
    })
};
HomeScript.billSubmit = function () {
    if (HomeScript.currentDesk.deskItem.des_id <= 0) {
        bootbox.alert('Bạn cần chọn một bàn để thanh toán');
        return false;
    }
    if (HomeScript.currentDesk.billInfo.totalMoney <= 0 || HomeScript.currentDesk.menuList.length <= 0) {
        bootbox.alert('Không thể thanh toán hóa đơn trống');
        return false;
    }
    if (HomeScript.currentDesk.billInfo.debit && !HomeScript.flagSetDebit) {
        this.checkDebitSubmit();
        return false;
    }
    //thanh toán hóa đơn
    bootbox.confirm('Bạn chắc chắn muốn thanh toán hóa đơn này? Lưu ý: sau khi thanh toán bạn sẽ không thể chỉnh sửa được hóa đơn.', function (result) {
        if (result) {
            $.ajax({
                type: 'post',
                url: 'ajax.php',
                data: {
                    action: 'billSubmit',
                    desk_id: HomeScript.currentDesk.deskItem.des_id,
                    debit: HomeScript.currentDesk.billInfo.debitMoney,
                    date: HomeScript.currentDesk.billInfo.debitTime,
                    payType: HomeScript.currentDesk.billInfo.payType
                },
                dataType: 'json',
                success: function (resp) {
                    if (resp.error) {
                        bootbox.alert(resp.error);
                        HomeScript.flagSetDebit = false;
                        return false;
                    }
                    if (resp.success == 1) {
                        //xóa bàn
                        HomeScript.currentDesk.domElement.removeClass('active');
                        HomeScript.view.resetInput();
                        //Hiển thị in hóa đơn
                        bootbox.confirm('Thanh toán thành công! Bạn có muốn in hóa đơn?', function (result) {
                            if (result) {
                                var mwindow = new Mindows();
                                mwindow.width = 600;
                                mwindow.height = 600;
                                mwindow.resize = true;
                                mwindow.iframe('../printer/print_bill.php', 'In hóa đơn', {
                                    action: 'PRINT_SUCCESS_BILL',
                                    billID: resp.bii_id
                                })
                            }
                        })
                    }
                }
            })
        }
        else {
            HomeScript.flagSetDebit = false;
        }
    })

};
HomeScript.checkDebitSubmit = function () {
    //nếu chọn ghi nợ thì check xem có chọn khách hàng không
    if (HomeScript.currentDesk.billInfo.debit && HomeScript.currentDesk.billInfo.customerID == 0) {
        //không cho thanh toán
        bootbox.alert('Bạn cần chọn khách hàng để ghi nợ');
        return false;
    }
    if (HomeScript.currentDesk.billInfo.debit) {
        //show form ghi nợ
        var mindow = new Mindows();
        mindow.width = 400;
        mindow.height = 230;
        mindow.resize = true;
        mindow.iframe('debit_v2.php', 'Cài đặt công nợ khách hàng', {total: HomeScript.currentDesk.billInfo.finalMoney});
    }
};
HomeScript.deleteDesk = function (elem) {
    //xóa bàn
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    if (!desk_id) {
        bootbox.alert('Chọn bàn để xóa');
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'deleteDesk', desk_id: desk_id},
        dataType: 'json',
        success: function (resp) {
            if (resp.success == 1) {
                bootbox.alert('Bàn đã được hủy', function () {
                    window.location.reload();
                });
            }
        }
    })
};
/* Hàm xóa menu trong bảng menu đã chọn*/
HomeScript.deleteMenu = function (menu_id) {
    if (!menu_id) {
        bootbox.alert('Chọn thực đơn để xóa');
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'deleteMenu', menu_id: menu_id, desk_id: HomeScript.currentDesk.deskItem.des_id},
        dataType: 'json',
        success: function (resp) {
            if (resp.success == 1) {
                HomeScript.removeMenu(menu_id);
                HomeScript.view.buildCurrentDesk();
            } else {
                bootbox.alert(resp.error);
            }
        }
    })
};
HomeScript.removeMenu = function (menu_id) {
    for (var i in HomeScript.currentDesk.menuList) {
        if (menu_id == HomeScript.currentDesk.menuList[i].men_id) {
            console.log(menu_id);
            HomeScript.currentDesk.menuList.splice(i, 1);
            break;
        }
    }
};
HomeScript.removeElementFromArray = function (array, list_index) {
    var arr = $.grep(array, function (n, i) {
        return $.inArray(i, list_index) == -1;
    });
    return arr;
};
HomeScript.removeDiacritics = function (str) {
    var diacriticsMap = {
        A: /[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g,
        AA: /[\uA732]/g,
        AE: /[\u00C6\u01FC\u01E2]/g,
        AO: /[\uA734]/g,
        AU: /[\uA736]/g,
        AV: /[\uA738\uA73A]/g,
        AY: /[\uA73C]/g,
        B: /[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g,
        C: /[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g,
        D: /[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g,
        DZ: /[\u01F1\u01C4]/g,
        Dz: /[\u01F2\u01C5]/g,
        E: /[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g,
        F: /[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g,
        G: /[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g,
        H: /[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g,
        I: /[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g,
        J: /[\u004A\u24BF\uFF2A\u0134\u0248]/g,
        K: /[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g,
        L: /[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g,
        LJ: /[\u01C7]/g,
        Lj: /[\u01C8]/g,
        M: /[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g,
        N: /[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g,
        NJ: /[\u01CA]/g,
        Nj: /[\u01CB]/g,
        O: /[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g,
        OI: /[\u01A2]/g,
        OO: /[\uA74E]/g,
        OU: /[\u0222]/g,
        P: /[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g,
        Q: /[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g,
        R: /[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g,
        S: /[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g,
        T: /[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g,
        TZ: /[\uA728]/g,
        U: /[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g,
        V: /[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g,
        VY: /[\uA760]/g,
        W: /[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g,
        X: /[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g,
        Y: /[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g,
        Z: /[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g,
        a: /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g,
        aa: /[\uA733]/g,
        ae: /[\u00E6\u01FD\u01E3]/g,
        ao: /[\uA735]/g,
        au: /[\uA737]/g,
        av: /[\uA739\uA73B]/g,
        ay: /[\uA73D]/g,
        b: /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g,
        c: /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g,
        d: /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g,
        dz: /[\u01F3\u01C6]/g,
        e: /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g,
        f: /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g,
        g: /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g,
        h: /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g,
        hv: /[\u0195]/g,
        i: /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g,
        j: /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g,
        k: /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g,
        l: /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g,
        lj: /[\u01C9]/g,
        m: /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g,
        n: /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g,
        nj: /[\u01CC]/g,
        o: /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g,
        oi: /[\u01A3]/g,
        ou: /[\u0223]/g,
        oo: /[\uA74F]/g,
        p: /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g,
        q: /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g,
        r: /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g,
        s: /[\u0073\u24E2\uFF53\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g,
        ss: /[\u00DF]/g,
        t: /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g,
        tz: /[\uA729]/g,
        u: /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g,
        v: /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g,
        vy: /[\uA761]/g,
        w: /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g,
        x: /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g,
        y: /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g,
        z: /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g
    };
    for (var x in diacriticsMap) {
        // Iterate through each keys in the above object and perform a replace
        str = str.replace(diacriticsMap[x], x);
    }
    return str;
}
HomeScript.searchText = function (string, needle) {
    var _string = HomeScript.removeDiacritics(string.toLowerCase());
    var _needle = HomeScript.removeDiacritics(needle.toLowerCase());
    return _needle.search(_string) != -1;
};
HomeScript.init = function () {
    HomeScript.listMenu = data_list_menu.slice();
    CrmUtilities.adjustScreen();
    this.view.adjustScreen();
    $('.scrollable-area').enscroll({
        showOnHover: true,
        minScrollbarLength: 28,
        addPaddingToPane: false
    });
    //cấu hình numeric
    this.domElement.menuNumber.autoNumeric({
        lZero: 'deny',
        mDec: 1
    });
    this.domElement.menuDiscount.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.customerDiscount.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.extraFee.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.vat.autoNumeric({
        lZero: 'deny',
        vMax: 100,
        mDec: 1
    });
    this.domElement.customerCash.autoNumeric({
        lZero: 'deny',
        mDec: 0
    });
    this.contextMenu();
};
HomeScript.inputChangeFunction = function (type) {
    var action = '';
    var data = {};
    switch (type) {
        case 'menu_number':
            action = 'updateMenuNumber';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                menu_id: HomeScript.currentMenu.menuItem.men_id,
                number: HomeScript.currentMenu.menuItem.cdm_number
            };
            break;
        case 'menu_discount':
            action = 'updateMenuDiscount';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                menu_id: HomeScript.currentMenu.menuItem.men_id,
                discount: HomeScript.currentMenu.menuItem.cdm_menu_discount
            };
            break;
        case 'customer':
            action = 'updateCustomer';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                cus_id: HomeScript.currentDesk.billInfo.customerID
            };
            break;
        case 'staff':
            action = 'updateStaff';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                staff_id: HomeScript.currentDesk.billInfo.staffID
            };
            break;
        case 'note':
            action = 'updateNote';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                note: HomeScript.currentDesk.billInfo.note
            };
            break;
        case 'vat':
            action = 'updateVAT';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                vat: HomeScript.currentDesk.billInfo.VAT
            };
            break;
        case 'extra_fee':
            action = 'updateExtraFee';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                extra_fee: HomeScript.currentDesk.billInfo.extraFee
            };
            break;
        case 'customer_discount':
            action = 'updateCustomerDiscount';
            data = {
                action: action,
                desk_id: HomeScript.currentDesk.deskItem.des_id,
                discount: HomeScript.currentDesk.billInfo.customerDiscount
            };
            break;
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: data,
        dataType: 'json',
        success: function (resp) {
            loadingProgress('hide');
            if (resp.error) {
                //đặt lại dữ liệu trước khi thay đổi
                switch (data.action) {
                    case 'updateMenuNumber':
                        HomeScript.currentMenu.menuItem.cdm_number = HomeScript.beforeData.currentMenu.menuItem.cdm_number;
                        for (var i in HomeScript.currentDesk.menuList) {
                            var tmpMenu = HomeScript.currentDesk.menuList[i];
                            if (tmpMenu.men_id == data.menu_id) {
                                tmpMenu.cdm_number = HomeScript.beforeData.currentMenu.menuItem.cdm_number;
                            }
                        }
                        HomeScript.view.buildCurrentDesk();
                        break;
                }
                bootbox.alert(resp.error);
            }
        },
        beforeSend: function () {
            loadingProgress('show');
        }
    })
};
HomeScript.keyUpFunction = function (type) {
    trigger_keyup = type;
    //kiểm tra xem có bàn hiện tại và thực đơn hiện tại ko
    if (!this.currentDesk.deskItem || $.isEmptyObject(this.currentDesk.deskItem)) {
        bootbox.alert('Bàn chưa được mở. Vui lòng mở bàn');
        return false;
    }
    if (!this.currentMenu.menuItem || $.isEmptyObject(this.currentMenu.menuItem)) {
        bootbox.alert('Vui lòng chọn thực đơn cho bàn!');
        return false;
    }
    //lưu lại giá trị cũ
    this.beforeData.currentDesk = $.extend(true, {}, this.currentDesk);
    this.beforeData.currentMenu = $.extend(true, {}, this.currentMenu);

    //cập nhật số lượng, phụ phí, VAT...
    var curMenu = this.currentMenu.menuItem,
        billInfo = this.currentDesk.billInfo,
        domElement = this.domElement;
    curMenu.cdm_number = parseFloat(domElement.menuNumber.autoNumeric('get'));
    curMenu.cdm_menu_discount = parseFloat(domElement.menuDiscount.autoNumeric('get'));
    billInfo.extraFee = parseFloat(domElement.extraFee.autoNumeric('get'));
    billInfo.VAT = parseFloat(domElement.vat.autoNumeric('get'));
    billInfo.customerDiscount = parseFloat(domElement.customerDiscount.autoNumeric('get'));
    this.currentDesk.menuList.map(function (menuItem) {
        if (menuItem.men_id == curMenu.men_id) {
            menuItem.cdm_number = curMenu.cdm_number;
        }
    });
    this.cashCurrentBill();
    var customerCash = HomeScript.domElement.customerCash.autoNumeric('get');
    React.render(React.createElement(FormattedNumber, {value: customerCash - billInfo.finalMoney, style: "currency", 
                                  currency: "VND"}), HomeScript.domElement.customerCashText[0]);
    this.view.buildCurrentDesk();
};

HomeScript.openDesk = function (elem) {
    var _this = $(elem);
    var deskData = _this.data();
    //nếu bàn đang mở rồi thì chỉ select bình thường
    if (_this.hasClass('active')) {
        HomeScript.selectDesk(elem);
        return true;
    }
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {action: 'openDesk', desk_id: deskData.des_id},
        dataType: 'json',
        success: function (resp) {
            if (resp.error) {
                bootbox.alert(resp.error);
                return false;
            } else {
                if (resp.array_menu) {
                    //active bàn
                    HomeScript.view.activeDesk(deskData.des_id);
                    HomeScript.parseResponseCurrentData(resp);
                    //tính tiền
                    HomeScript.cashCurrentBill();
                    HomeScript.view.buildCurrentDesk();
                    HomeScript.view.selectedCurrentMenu();
                }
            }
        }
    })
};
HomeScript.setDebit = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        $('#is-debit').removeAttr('checked');
        bootbox.alert('Bạn cần chọn bàn để ghi nợ!');
        return false;
    }
    HomeScript.currentDesk.billInfo.debit = $('#is-debit').is(':checked');
    HomeScript.flagSetDebit = false;
};
HomeScript.selectDesk = function (elem) {
    var _this = $(elem);
    //nếu bàn đang được chọn thì không làm gì cả
    if (_this.hasClass('selected')) {
        return false;
    }
    this.resetCurrentData();
    //nếu bàn này đang active thì không cần resetInput
    if (!_this.hasClass('active')) {
        this.view.resetInput();
    }
    var deskData = _this.data();
    this.currentDesk.domElement = _this;
    this.currentDesk.deskItem = new DeskItem(deskData);
    this.domElement.currentDeskName.html(deskData.full_name);

    //select bàn này
    this.view.selectedCurrentDesk();
    //nếu bàn đang active thì load chi tiết bàn
    if (_this.hasClass('active')) {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {action: 'getCurrentDeskDetail', desk_id: HomeScript.currentDesk.deskItem.des_id},
            dataType: 'json',
            success: function (resp) {
                HomeScript.parseResponseCurrentData(resp);
                //tính tiền
                HomeScript.cashCurrentBill();
                HomeScript.view.buildCurrentDesk();
                HomeScript.view.selectedCurrentMenu();
            }
        })
    }
};
//Chọn thực đơn trong bàn
HomeScript.selectMenuInDesk = function (menu_id) {
    //gán thực đơn hiện tại
    HomeScript.currentMenu.domElement = $('#record_' + menu_id);
    var tmpMn;
    HomeScript.currentDesk.menuList.map(function (menuItem) {
        if (menuItem.men_id == menu_id) {
            tmpMn = menuItem;
        }
    });
    HomeScript.currentMenu.menuItem = new MenuInDesk(tmpMn);
    //đổi màu active cho thực đơn
    HomeScript.view.selectedCurrentMenu();
    HomeScript.view.fillDataToInput();
};
HomeScript.parseResponseCurrentData = function (resp) {
    //load thông tin hóa đơn
    this.currentDesk.billInfo.customerCode = resp.customer_code;
    this.currentDesk.billInfo.customerDiscount = parseFloat(resp.cud_customer_discount);
    this.currentDesk.billInfo.customerID = resp.cud_customer_id;
    this.currentDesk.billInfo.customerName = resp.customer_name;
    this.currentDesk.billInfo.debit = Boolean(parseInt(resp.cud_debit));
    this.currentDesk.billInfo.extraFee = parseFloat(resp.cud_extra_fee);
    this.currentDesk.billInfo.note = resp.cud_note;
    this.currentDesk.billInfo.payType = resp.cud_pay_type;
    this.currentDesk.billInfo.staffCode = resp.staff_code;
    this.currentDesk.billInfo.staffName = resp.staff_name;
    this.currentDesk.billInfo.staffID = resp.cud_staff_id;
    this.currentDesk.billInfo.startTime = resp.cud_start_time;
    this.currentDesk.billInfo.startTimeStr = resp.start_time_string;
    this.currentDesk.billInfo.VAT = parseFloat(resp.cud_vat);
    //load thông tin thực đơn
    if (resp.hasOwnProperty('array_menu')) {
        //reset lại menuList
        this.currentDesk.menuList = [];
        for (i in resp.array_menu) {
            var menuTmp = new MenuInDesk(resp.array_menu[i]);
//                console.log(menuTmp);
            this.currentDesk.menuList.push(menuTmp);
        }
    }
    //gán thực đơn đầu tiên trong list làm currentMenu
    this.currentMenu.menuItem = this.currentDesk.menuList[0];
};
HomeScript.parseResponseAddMenuToDesk = function (resp) {
    if (resp.hasOwnProperty('array_menu')) {
        //reset lại menuList
        this.currentDesk.menuList = [];
        for (i in resp.array_menu) {
            var menuTmp = new MenuInDesk(resp.array_menu[i]);
            this.currentDesk.menuList.push(menuTmp);
        }
    }
};
HomeScript.resetCurrentData = function () {
    this.flagSetDebit = false;
    this.currentDesk.domElement = null;
    this.currentDesk.menuList = [];
    this.currentDesk.deskItem = new DeskItem();
    this.currentDesk.billInfo = new BillInfo();
    this.currentMenu.domElement = null;
    this.currentMenu.menuItem = new MenuInDesk();
};

HomeScript.view = {};
HomeScript.view.activeDesk = function (des_id) {
    HomeScript.domElement.listingDesk.find('[data-des_id=' + des_id + ']').addClass('active');
};
HomeScript.view.adjustScreen = function () {
    HomeScript.domElement.mainSale.height($('.section-content').height() - 160);
    HomeScript.domElement.centerListing.height(HomeScript.domElement.mainSale.height() - 140);
    HomeScript.domElement.listingMenu.height(HomeScript.domElement.sectionContent.height()
        - $('#search-menu').height() - 180);
    HomeScript.domElement.listingDesk.height(HomeScript.domElement.listingMenu.height());
};

HomeScript.view.buildListMenu = function () {
    React.render(React.createElement(MenuList, {list_category_menu: HomeScript.listMenu}), HomeScript.domElement.listingMenu[0]);
};
HomeScript.view.buildCurrentDesk = function () {
    //render vào center_listing
    React.render(React.createElement(HomeScript.react.TableMenu, null), HomeScript.domElement.centerListing[0]);
    //cấp phát menu phải
    HomeScript.contextMenu();
    //fill dữ liệu vào các input
    this.fillDataToInput();
    //bỏ disable ở các input
    this.switchDisableInput(false);
    //cấp phát thanh cuộn
    this.reInitScroll();
};
HomeScript.view.collapse = function (collapse_id) {
    var ul = $('ul[data-collapse-id=' + collapse_id + ']');
    if (ul.hasClass('list-cat-child')) {
        ul.siblings('label').find('.fa').toggleClass('fa-minus-square-o').toggleClass('fa-plus-square-o');
    }
    if (ul.hasClass('list-menu-child')) {
        ul.siblings('label').find('.fa').toggleClass('fa-caret-right').toggleClass('fa-caret-down');
    }
    ul.slideToggle();
};
HomeScript.view.changeCustomer = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return false;
    }
    var mindow = new Mindows;
    mindow.width = 930;
    mindow.height = 450;
    mindow.resize = true;
    mindow.iframe(HomeScript.ajaxExtendUrl.loadModalSelectCustomer, 'Quản lý thông tin khách hàng');
};
HomeScript.view.changeStaff = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return false;
    }
    var mindow = new Mindows;
    mindow.width = 930;
    mindow.height = 450;
    mindow.resize = true;
    mindow.iframe(HomeScript.ajaxExtendUrl.loadModalSelectStaff, 'Quản lý thông tin nhân viên');
};
HomeScript.view.changePayType = function (pay_type) {
    HomeScript.currentDesk.billInfo.payType = pay_type;
};
HomeScript.view.fillDataToInput = function () {
    var billInfo = HomeScript.currentDesk.billInfo,
        domElement = HomeScript.domElement,
        extraFeeText = number_format(billInfo.totalMoney * billInfo.extraFee / 100),
        customerDiscountText = number_format(billInfo.totalMoney * billInfo.customerDiscount / 100),
        vatText = number_format(billInfo.totalMoney * (1 + billInfo.extraFee / 100 - billInfo.customerDiscount / 100) * billInfo.VAT / 100);
    domElement.billNote.val(billInfo.note);
    domElement.startTimeString.val(billInfo.startTimeStr);
    domElement.customerCode.val(billInfo.customerCode);
    domElement.searchCustomer.val(billInfo.customerName);
    domElement.staffCode.val(billInfo.staffCode);
    domElement.searchStaff.val(billInfo.staffName);
    //nếu đang keyup ở các input thì không set value ở input đó
    if (trigger_keyup != 'extraFee') {
        domElement.extraFee.autoNumeric('set', billInfo.extraFee);
    }
    domElement.extraFeeText.html(extraFeeText);
    if (trigger_keyup != 'customerDiscount') {
        domElement.customerDiscount.autoNumeric('set', billInfo.customerDiscount);
    }
    domElement.customerDiscountText.html(customerDiscountText);
    if (trigger_keyup != 'vat') {
        domElement.vat.autoNumeric('set', billInfo.VAT);
    }
    domElement.vatExt.html(vatText);

    domElement.totalMoney.html(number_format(billInfo.totalMoney));
    domElement.finalMoney.html(number_format(billInfo.finalMoney) + ' VNĐ');
    //cập nhật các input chứa dữ liệu của thực đơn
    if (HomeScript.currentMenu.menuItem) {
        var menuItem = HomeScript.currentMenu.menuItem;
        domElement.menuImage.attr('src', menuItem.men_image);
        if (trigger_keyup != 'menuDiscount') {
            domElement.menuDiscount.autoNumeric('set', menuItem.cdm_menu_discount);
        }
        domElement.menuName.html(menuItem.men_name);
        if (trigger_keyup != 'menuNumber') {
            domElement.menuNumber.autoNumeric('set', number_format(menuItem.cdm_number));
        }
        domElement.menuPrice.removeClass('active').html(number_format(menuItem.men_price));
        domElement.menuPrice1.removeClass('active').html(number_format(menuItem.men_price1));
        domElement.menuPrice2.removeClass('active').html(number_format(menuItem.men_price2));
        switch (menuItem.cdm_price_type) {
            case 'men_price':
                HomeScript.domElement.menuPrice.addClass('active');
                break;
            case 'men_price1':
                HomeScript.domElement.menuPrice1.addClass('active');
                break;
            case 'men_price2':
                HomeScript.domElement.menuPrice2.addClass('active');
                break;
        }
    }
    //reset trigger keyup
    trigger_keyup = '';
};
HomeScript.view.joinDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        bootbox.alert('Phải chọn bàn để ghép');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 450;
    mwindow.height = 250;
    mwindow.resize = true;
    mwindow.iframe('join_desk.php', 'Ghép bàn ăn', {desk_id: desk_id});
};
HomeScript.view.moveDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        bootbox.alert('Phải chọn bàn ăn để chuyển');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 450;
    mwindow.height = 250;
    mwindow.resize = true;
    mwindow.iframe('move_desk.php', 'Chuyển bàn ăn', {desk_id: desk_id});
};
HomeScript.view.printBills = function () {
    if (HomeScript.currentDesk.deskItem.des_id && HomeScript.currentDesk.menuList.length) {
        // lấy id bàn và menu để in hóa đơn
        var desk_id = HomeScript.currentDesk.deskItem.des_id;
        var mwindow = new Mindows();
        mwindow.width = 600;
        mwindow.height = 600;
        mwindow.resize = true;
        mwindow.iframe('../printer/index.php', 'In hóa đơn tạm tính', {
            action: "PRINT_BEFORE",
            desk_id: desk_id
        });
    } else {
        bootbox.alert('Chọn bàn để in. Lưu ý bàn không có thực đơn không thể in');
    }
};
HomeScript.view.printOrder = function () {
    if (HomeScript.currentDesk.deskItem.des_id && HomeScript.currentDesk.menuList.length) {
        // lấy id bàn và menu để in hóa đơn
        var desk_id = HomeScript.currentDesk.deskItem.des_id;
        var mwindow = new Mindows();
        mwindow.width = 600;
        mwindow.height = 400;
        mwindow.resize = true;
        mwindow.iframe('../printer/order.php', 'In chế biến', {
            desk_id: desk_id
        });
    } else {
        bootbox.alert('Chọn bàn để in');
    }
};
//reset input
HomeScript.view.resetInput = function () {
    this.switchDisableInput(true);
    //dữ liệu đã được reset
    HomeScript.resetCurrentData();
    this.fillDataToInput();
    //reset dữ liệu trong bàn
    HomeScript.react.tableMenu = React.createElement("table", null);
    React.render(HomeScript.react.tableMenu, HomeScript.domElement.centerListing[0]);
};
HomeScript.view.reInitScroll = function () {
    $('.scrollable-area').each(function () {
        if (!$(this).parent().find('.enscroll-track').length) {
            console.log($(this));
            $(this).enscroll({
                showOnHover: true,
                minScrollbarLength: 28,
                addPaddingToPane: false
            })
        }
    });
};
HomeScript.view.searchMenu = function () {
    var text = $.trim(HomeScript.domElement.searchMenuText.val());
    HomeScript.listMenu = $.extend(true, [], data_list_menu);
    if (!$.trim(text)) {
        HomeScript.view.buildListMenu();
        return true;
    }
    var cat_position = [];
    for (var i in HomeScript.listMenu) {
        var cat_parent = HomeScript.listMenu[i];
        var cat_child_position = [];
        for (var j in cat_parent.list_cat_child) {
            var cat_child = cat_parent.list_cat_child[j];
            var menu_child_position = [];
            for (var k in cat_child.list_menu_child) {
                var menu = cat_child.list_menu_child[k];
                //console.log(menu.men_name.search(regex));
                if (!HomeScript.searchText(text, menu.men_name)) {
                    menu_child_position.push(parseInt(k));
                }
            }
            cat_child.list_menu_child = HomeScript.removeElementFromArray(cat_child.list_menu_child, menu_child_position);
            if (!cat_child.list_menu_child.length) {
                cat_child_position.push(parseInt(j));
            }
        }
        cat_parent.list_cat_child = HomeScript.removeElementFromArray(cat_parent.list_cat_child, cat_child_position);
        if (!cat_parent.list_cat_child.length) {
            cat_position.push(parseInt(i));
        }
    }
    HomeScript.listMenu = HomeScript.removeElementFromArray(HomeScript.listMenu, cat_position);
    //generate lại list menu
    HomeScript.view.buildListMenu();
};
HomeScript.view.selectedDesk = function (des_id) {
    //bỏ select ở các bàn khác
    HomeScript.domElement.listingDesk.find('.desk-item').removeClass('selected');
    //gán class select
    $('[data-des_id=' + des_id + ']').addClass('selected');
};
HomeScript.view.splitDesk = function () {
    if (!HomeScript.currentDesk.deskItem.des_id) {
        bootbox.alert('Phải chọn bàn để tách hóa đơn');
        return false;
    }
    var desk_id = HomeScript.currentDesk.deskItem.des_id;
    var mwindow = new Mindows();
    mwindow.width = 900;
    mwindow.height = 400;
    mwindow.resize = true;
    mwindow.iframe('split_desk.php', 'Tách hóa đơn', {desk_id: desk_id});
};
HomeScript.view.selectedCurrentDesk = function () {
    if (!HomeScript.currentDesk.deskItem) {
        return false;
    }
    HomeScript.domElement.listingDesk.find('.desk-item').removeClass('selected');
    //gán class select
    HomeScript.currentDesk.domElement.addClass('selected');
};
HomeScript.view.selectedMenu = function (menu_id) {
    //bỏ active ở các menu khác
    HomeScript.domElement.centerListing.find('.record-item').removeClass('active');
    HomeScript.domElement.centerListing.find('#record_' + menu_id).addClass('active');
};
HomeScript.view.selectedCurrentMenu = function () {
    if (!HomeScript.currentMenu.menuItem) {
        return false;
    }
    HomeScript.currentMenu.domElement = $('.record-item#record_' + HomeScript.currentMenu.menuItem.men_id);
    HomeScript.domElement.centerListing.find('.record-item').removeClass('active');
    HomeScript.currentMenu.domElement.addClass('active');
    this.selectedPriceMenu(HomeScript.currentMenu.menuItem.cdm_price);
};
HomeScript.view.selectedPriceMenu = function (price_type) {
    HomeScript.domElement.menuPrice.removeClass('active');
    HomeScript.domElement.menuPrice1.removeClass('active');
    HomeScript.domElement.menuPrice2.removeClass('active');
    switch (price_type) {
        case 'men_price':
        default :
            HomeScript.domElement.menuPrice.addClass('active');
            break;
        case 'men_price1':
            HomeScript.domElement.menuPrice1.addClass('active');
            break;
        case 'men_price2':
            HomeScript.domElement.menuPrice2.addClass('active');
            break;
    }
};
HomeScript.view.settingMenuDiscount = function () {
    var result_percent = 0, result_money = 0;
    //nếu current_menu không tồn tại thì return luôn
    if (!HomeScript.currentMenu.menuItem.men_id) {
        return false;
    }
    var mindow = new Mindows();
    mindow.width = 450;
    mindow.height = 250;
    mindow.resize = true;
    var content = $('#convertPercentTemplate').html();
    mindow.openStatic(content, function () {
        var sl = HomeScript.currentMenu.menuItem.cdm_number;
        var dg = HomeScript.currentMenu.menuItem.cdm_price;
        var total_money = number_format(sl * dg);
        mindow.container.find('#total_money').val(total_money);
        callbackConvertPercent(mindow);
        //bắt sự kiện click đồng ý chuyển đổi
        mindow.container.find('#acceptConvert').unbind('click').click(function () {
            result_percent = $('#convert_result').val();
            result_money = $('#total_money').autoNumeric('get') - $('#convert_money').autoNumeric('get');
            HomeScript.currentMenu.menuItem.cdm_menu_discount = result_percent;
            HomeScript.currentDesk.menuList.map(function (item) {
                if (item.men_id == HomeScript.currentMenu.menuItem.men_id) {
                    item.cdm_menu_discount = parseFloat(result_percent);
                }
            });
            HomeScript.domElement.menuDiscount.trigger('change');
            //tính lại tiền
            HomeScript.cashCurrentBill();
            HomeScript.view.buildCurrentDesk();
            //đóng mindow
            mindow.close();
        })
    });
};

/* Chuyển đổi từ tiền sang % ở mục phụ phí*/
HomeScript.view.settingMenu = function () {
    var result_percent = 0, result_money = 0;
    //nếu current_menu không tồn tại thì return luôn
    if (!HomeScript.currentMenu.menuItem.men_id) {
        return false;
    }
    var mindow = new Mindows();
    mindow.width = 450;
    mindow.height = 250;
    mindow.resize = true;
    var content = $('#convertPercentTemplate').html();
    mindow.openStatic(content, function () {
        var sl = HomeScript.currentMenu.menuItem.cdm_number;
        var dg = HomeScript.currentMenu.menuItem.cdm_price;
        var total_money = number_format(sl * dg);
        mindow.container.find('#total_money').val(total_money);
        callbackConvertPercent(mindow);
        //bắt sự kiện click đồng ý chuyển đổi
        mindow.container.find('#acceptConvert').unbind('click').click(function () {
            result_percent = $('#convert_result').val();
            result_money = $('#total_money').autoNumeric('get') - $('#convert_money').autoNumeric('get');
            HomeScript.currentMenu.menuItem.cdm_menu_discount = result_percent;
            HomeScript.currentDesk.menuList.map(function (item) {
                if (item.men_id == HomeScript.currentMenu.menuItem.men_id) {
                    item.cdm_menu_discount = parseFloat(result_percent);
                }
            });
            HomeScript.domElement.menuDiscount.trigger('change');
            //tính lại tiền
            HomeScript.cashCurrentBill();
            HomeScript.view.buildCurrentDesk();
            //đóng mindow
            mindow.close();
        })
    });
};

HomeScript.view.switchDisableInput = function (disabled) {
    HomeScript.domElement.menuPrice.removeClass('active');
    HomeScript.domElement.menuPrice1.removeClass('active');
    HomeScript.domElement.menuPrice2.removeClass('active');
    HomeScript.domElement.debitCheckbox.removeAttr('checked');
    if (disabled) {
        HomeScript.domElement.menuNumber.attr('disabled', 'disabled');
        HomeScript.domElement.menuDiscount.attr('disabled', 'disabled');
        HomeScript.domElement.extraFee.attr('disabled', 'disabled');
        HomeScript.domElement.staffCode.attr('disabled', 'disabled');
        HomeScript.domElement.customerCode.attr('disabled', 'disabled');
        HomeScript.domElement.searchStaff.attr('disabled', 'disabled');
        HomeScript.domElement.searchCustomer.attr('disabled', 'disabled');
        HomeScript.domElement.customerDiscount.attr('disabled', 'disabled');
        HomeScript.domElement.vat.attr('disabled', 'disabled');
        HomeScript.domElement.customerCash.attr('disabled', 'disabled');
    } else {
        HomeScript.domElement.menuNumber.removeAttr('disabled');
        HomeScript.domElement.menuDiscount.removeAttr('disabled');
        HomeScript.domElement.extraFee.removeAttr('disabled');
        HomeScript.domElement.staffCode.removeAttr('disabled');
        HomeScript.domElement.customerCode.removeAttr('disabled');
        HomeScript.domElement.searchStaff.removeAttr('disabled');
        HomeScript.domElement.searchCustomer.removeAttr('disabled');
        HomeScript.domElement.customerDiscount.removeAttr('disabled');
        HomeScript.domElement.vat.removeAttr('disabled');
        HomeScript.domElement.customerCash.removeAttr('disabled');
    }
};

//công thức tính tiền của 1 thực đơn
HomeScript.cashMenu = function (price, number, discount) {
    price = parseFloat(price);
    number = parseFloat(number);
    discount = parseFloat(discount);
    return parseFloat(price * number * (100 - discount) / 100);
};
HomeScript.cashMenuItem = function (menuItem) {
    return this.cashMenu(menuItem.cdm_price, menuItem.cdm_number, menuItem.cdm_menu_discount);
};
HomeScript.cashCurrentBill = function () {
    var total_money = 0, final_money = 0, billInfo = this.currentDesk.billInfo;
    this.currentDesk.menuList.map(function (menuItem) {
        total_money += HomeScript.cashMenuItem(menuItem);
    });
    billInfo.totalMoney = total_money;
    final_money = (total_money * (100 + billInfo.extraFee - billInfo.customerDiscount) / 100) * (100 + billInfo.VAT) / 100;
    billInfo.finalMoney = final_money;
};

//context menu
HomeScript.contextMenu = function () {
    //context table
    $.contextMenu({
        selector: '.menu-desk-menu',
        items: {
            delete: {
                name: '<i class="fa fa-trash"></i>  Xóa thực đơn này',
                callback: function (key, opt) {
                    var _this = $(this);
                    bootbox.confirm('Bạn muốn hủy thực đơn này?', function (result) {
                        if (result) {
                            HomeScript.deleteMenu(_this.attr('data-id'));
                        }
                    });
                }
            }
        }
    });
    //context menu
    $.contextMenu({
        selector: '.desk-item',
        items: {
            active: {
                name: '<i class="fa fa-play"></i> Sử dụng',
                callback: function (key, opt) {
                    var _this = $(this);
                    if (_this.hasClass('active'))
                        HomeScript.selectDesk(_this);
                    else {
                        HomeScript.selectDesk(_this);
                        HomeScript.openDesk(_this);
                    }

                }
            },
            payment: {
                name: '<i class="fa fa-check"></i> Thanh toán hóa đơn',
                callback: function (key, opt) {
                    HomeScript.billSubmit();
                }
            },
            cancel: {
                name: '<i class="fa fa-times"></i> Hủy hóa đơn',
                callback: function (key, opt) {
                    var _this = $(this);
                    bootbox.confirm('Bạn muốn hủy bàn này?', function (result) {
                        if (result) {
                            HomeScript.deleteDesk(_this);
                        }
                    })
                }
            },
            print: {
                name: '<i class="fa fa-print"></i> In tạm tính',
                callback: function (key, opt) {
                    HomeScript.view.printBills();
                }
            },
            printmenu: {
                name: '<i class="fa fa-print"></i> In chế biến',
                callback: function (key, opt) {
                    HomeScript.view.printOrder();

                }
            },
            fowardesk: {
                name: '<i class="fa fa-exchange"></i> Chuyển bàn',
                callback: function (key, opt) {
                    HomeScript.view.moveDesk();
                }
            },
            split: {
                name: '<i class="fa fa-files-o"></i> Tách hóa đơn',
                callback: function (key, opt) {
                    HomeScript.view.splitDesk();
                }
            },
            join: {
                name: '<i class="fa fa-file-text"></i> Ghép hóa đơn',
                callback: function (key, opt) {
                    HomeScript.view.joinDesk();
                }
            },
            listdesk: {
                name: '<i class="fa fa-list"></i> Quản lý danh sách bàn',
                callback: function (key, opt) {
                    var mindow = new Mindows;
                    mindow.width = 930;
                    mindow.height = 450;
                    mindow.resize = true;
                    mindow.iframe(HomeScript.ajaxExtendUrl.loadMindowListDesk, 'Quản lý danh sách bàn ăn');
                }
            },
            refresh: {
                name: '<i class="fa fa-refresh"></i> Tải lại danh sách bàn',
                callback: function (key, opt) {
                    window.location.reload();
                }
            }
        }
    });
};
HomeScript.init();

function callbackConvertPercent(mindow) {
    //cấp phát numeric
    mindow.container.find('#total_money').autoNumeric({
        lZero: 'deny',
        mDec: 0
    });

    mindow.container.find('#convert_money').autoNumeric({
        lZero: 'deny',
        mDec: 0,
        vMin: 0,
        vMax: mindow.container.find('#total_money').autoNumeric('get')
    });

    //bắt sự kiện keypress vào convert_money
    $('#convert_money').keyup(function () {
        var money = $(this).autoNumeric('get');
        var total = $('#total_money').autoNumeric('get');
        $('#convert_result').val(money / total * 100);
    });

    mindow.container.find('#cancelConvert').unbind('click').click(function () {
        mindow.close();
    })
}

function communicateParentWindow(action, data) {
    //nếu không có current_desk thì không cho thực hiện
    if (!HomeScript.currentDesk.deskItem.des_id) {
        return;
    }
    switch (action) {
        case 'selectCustomer':
            //cập nhật customer
            $.ajax({
                type: 'post',
                url: 'ajax.php',
                data: {
                    action: 'updateCustomer',
                    desk_id: HomeScript.currentDesk.deskItem.des_id,
                    cus_id: data.cus_id
                },
                dataType: 'json',
                success: function (resp) {
                    loadingProgress('hide');
                    if (resp.error) {
                        bootbox.alert(resp.error);
                        return false;
                    }
                    $('.mwindow-close').trigger('click');
                    //cập nhật dữ liệu
                    HomeScript.currentDesk.billInfo.customerID = data.cus_id;
                    HomeScript.currentDesk.billInfo.customerCode = data.customer_code;
                    HomeScript.currentDesk.billInfo.customerName = data.customer_name;
                    HomeScript.currentDesk.billInfo.customerDiscount = data.customer_discount;
                    //tính lại tiền
                    HomeScript.cashCurrentBill();
                    HomeScript.view.fillDataToInput();
                },
                beforeSend: function () {
                    loadingProgress('show');
                }
            });
            break;
        case 'selectStaff':
            //cập nhật nhân viên
            $.ajax({
                type: 'post',
                url: 'ajax.php',
                data: {
                    action: 'updateStaff',
                    desk_id: HomeScript.currentDesk.deskItem.des_id,
                    staff_id: data.use_id
                },
                dataType: 'json',
                success: function (resp) {
                    loadingProgress('hide');
                    if (resp.error) {
                        bootbox.alert(resp.error);
                    }
                    //cập nhật dữ liệu
                    HomeScript.currentDesk.billInfo.staffID = data.use_id;
                    HomeScript.currentDesk.billInfo.staffCode = data.staff_code;
                    HomeScript.currentDesk.billInfo.staffName = data.staff_name;
                    HomeScript.view.fillDataToInput();
                    $('.mwindow-close').trigger('click');
                },
                beforeSend: function () {
                    loadingProgress('show');
                }
            });
            break;
        case 'moveDesk' :
        case 'joinDesk' :
            $('.mwindow-close').trigger('click');
            $('.desk-item[data-des_id=' + data.from_desk + ']').removeClass('active');
            $('.desk-item[data-des_id=' + data.to_desk + ']').addClass('active').trigger('click');
            break;
        case 'splitDesk':
            //đóng khung mindow
            $('.mwindow-close').trigger('click');
            //cập nhật dữ liệu của bàn
            //với dữ liệu data nhận được ta sẽ thay đổi số lượng thực đơn ở bàn hiện tại
            //đồng thời thêm active vào bàn mới được tách
            if (data.from_desk_id != HomeScript.currentDesk.deskItem.des_id) {
                return false;
            }
            HomeScript.currentDesk.menuList.map(function (itemInDesk, index) {
                if (data.from_list_menu.hasOwnProperty(itemInDesk.men_id)) {
                    itemInDesk.cdm_number = data.from_list_menu[itemInDesk.men_id].men_number;
                } else {
                    HomeScript.currentDesk.menuList.splice(index, 1);
                }
            });
            HomeScript.domElement.listingDesk.find('[data-des_id=' + data.to_desk_id + ']').addClass('active');
            //build lại thực đơn
            HomeScript.cashCurrentBill();
            HomeScript.view.buildCurrentDesk();
            HomeScript.view.selectedCurrentMenu();
            break;
        case 'setDebit':
            //đóng khung mindow
            $('.mwindow-close').trigger('click');
            HomeScript.currentDesk.billInfo.debitMoney = data.money;
            HomeScript.currentDesk.billInfo.debitTime = data.time;
            HomeScript.flagSetDebit = true;
            HomeScript.billSubmit();
            break;
        case 'printOrder':
            $('.mwindow-close').trigger('click');
            break;
        default :
            break;
    }
}