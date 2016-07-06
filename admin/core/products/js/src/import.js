function ProductItem(data) {
    var _default = {
        pro_id: 0,
        pro_name: '',
        pro_unit: '',
        pro_image: '',
        pro_code: '',
        pro_number: 0,
        pro_price: 0,
        pro_total: 0
    };
    $.extend(_default, data);
    this.pro_id = _default.pro_id;
    this.pro_name = _default.pro_name;
    this.pro_unit = _default.pro_unit;
    this.pro_image = _default.pro_image;
    this.pro_code = _default.pro_code;
    this.pro_price = parseFloat(_default.pro_price);
    this.pro_number = parseFloat(_default.pro_number);
    this.pro_total = this.pro_price * this.pro_number;
    this.getInstances = function () {
        return new ProductItem(_default);
    }
}
var ImportScript = ImportScript || {};
ImportScript.productActive = new ProductItem();
ImportScript.productListData = ImportScript.productListData || [];
ImportScript.productList = [];
ImportScript.generateProductList = function () {
    ImportScript.productList = [];
    for (var i in ImportScript.productListData) {
        ImportScript.productList.push(new ProductItem(ImportScript.productListData[i]));
    }
};
ImportScript.importList = [];
ImportScript.domElement = {
    listingProduct: $('#table-listing'),
    listingImport: $('#listing-import').find('.table-listing-bound'),
    productID: $('#product-id'),
    productNumber: $('#product-number'),
    productPrice: $('#product-price'),
    productName: $('#product-name'),
    debitInput: $('#check-debit'),
    debitInfo: $('#info-debit'),
    debitPrePay: $('#pre-pay'),
    debitSetDate: $('#debit-date'),
    totalMoney: $('#total-money')
};
ImportScript.billInfo = {
    startDate: $('#bio_start_time').val(),
    debit: false,
    debitMoney: 0,
    debitDate: 0,
    payType: 0,
    totalMoney: 0
};
var IntlMixin = ReactIntl.IntlMixin;
var FormattedNumber = ReactIntl.FormattedNumber;
ImportScript.react = {};
ImportScript.react.TableHead = React.createClass({
    render: function () {
        return <thead>
        <tr>
            <th width="32px;">STT</th>
            <th width="75px">
                <strong>Mã hàng</strong>
            </th>
            <th width="35%">
                <strong>Tên hàng</strong>
            </th>
            <th>
                <strong>ĐVT</strong>
            </th>
            <th>
                <strong>Số lượng</strong>
            </th>
            <th>
                <strong>Giá nhập</strong>
            </th>
            <th>
                <strong>Thành tiền</strong>
            </th>
        </tr>
        </thead>
    }
});

ImportScript.react.TableRow = React.createClass({
    mixins: [IntlMixin],
    render: function () {
        var element_id = 'record_' + this.props.id;
        var id = this.props.id;
        var onClickFn = function () {
            ImportScript.activeProductImport(id);
        };
        return (<tbody>
        <tr id={element_id} data-id={id} className="record-item import-item" onClick={onClickFn}>
            <td className="center">{this.props.stt}</td>
            <td className="center">{this.props.code}</td>
            <td>{this.props.name}</td>
            <td className="center">{this.props.unit}</td>
            <td className="center"><FormattedNumber value={this.props.number}/></td>
            <td className="text-right"><FormattedNumber value={this.props.price} style="currency" currency="VND"/></td>
            <td className="text-right"><FormattedNumber value={this.props.total} style="currency" currency="VND"/></td>
        </tr>
        </tbody>)
    }
});
ImportScript.react.TableImport = React.createClass({
    render: function () {
        var rowsProduct = [];
        for (var i in ImportScript.importList) {
            i = parseInt(i);
            var productItem = ImportScript.importList[i];
            rowsProduct.push(<ImportScript.react.TableRow
                stt={i+1}
                id={productItem.pro_id}
                code={productItem.pro_code}
                name={productItem.pro_name}
                unit={productItem.pro_unit}
                number={productItem.pro_number}
                price={productItem.pro_price}
                total={productItem.pro_total}
                />)
        }
        return <table className="table table-bordered table-hover table-listing">
            <ImportScript.react.TableHead />
            {rowsProduct}
            <tbody>
            <tr className="footer">
                <td colSpan="9">
                    <span class="fl nowrap">Tổng cộng <FormattedNumber value={ImportScript.importList.length}/> nguyên liệu</span>
                </td>
            </tr>
            </tbody>
        </table>
    }
});
ImportScript.react.renderTableImport = function () {
    if (ImportScript.productActive) {
        ImportScript.productActive.pro_number = $('#product-number').autoNumeric('get');
        ImportScript.productActive.pro_price = $('#product-price').autoNumeric('get');
        for (var i in ImportScript.importList) {
            if (ImportScript.importList[i].pro_id == ImportScript.productActive.pro_id) {
                ImportScript.importList[i] = new ProductItem(ImportScript.productActive);
            }
        }
    }
    ImportScript.calculateTotalMoney();
    if (ImportScript.billInfo.debit) {
        ImportScript.billInfo.debitDate = $('#debit-date').val();
        ImportScript.billInfo.debitMoney = $('#pre-pay').autoNumeric('get');
        React.render(<FormattedNumber
            value={ImportScript.billInfo.totalMoney - ImportScript.billInfo.debitMoney}/>, $('#money-debit')[0]);
    }
    React.render(<FormattedNumber value={ImportScript.billInfo.totalMoney}/>, ImportScript.domElement.totalMoney[0]);
    React.render(<ImportScript.react.TableImport />, ImportScript.domElement.listingImport[0])
};
ImportScript.init = function () {
    $('#product-number, #product-price, #pre-pay').autoNumeric({
        lZero: 'deny',
        mDec: 0
    }).keyup(function () {
        ImportScript.react.renderTableImport();
    });
    $('#debit-date').miniDatePicker({
        dp_position_y: true
    });
    this.domElement.listingImport.enscroll({
        showOnHover: true,
        minScrollbarLength: 28,
        addPaddingToPane: false
    });
    this.generateProductList();
    this.react.renderTableImport();
};
ImportScript.calculateTotalMoney = function () {
    this.billInfo.totalMoney = 0;
    for (var i in this.importList) {
        var productItem = this.importList[i];
        this.billInfo.totalMoney += parseInt(productItem.pro_price) * parseInt(productItem.pro_number);
    }
};
ImportScript.activeProductImport = function (pro_id) {
    this.productActive = this.getProductFromImport(pro_id);
    //console.log(pro_id);
    this.domElement.listingImport.find('.import-item').removeClass('active');
    this.domElement.listingImport.find('#record_' + this.productActive.pro_id).addClass('active');
    this.domElement.productID.val(this.productActive.pro_code);
    this.domElement.productName.val(this.productActive.pro_name);
    this.domElement.productPrice.val(this.productActive.pro_price);
    this.domElement.productNumber.val(this.productActive.pro_number);
    //console.log(this.productActive);
};
ImportScript.activeProductListing = function (pro_id) {

};
ImportScript.addProduct = function (pro_id) {
    if (!this.issetProduct(this.importList, pro_id)) {
        var data = this.getProductFromList(pro_id);
        data.pro_number = 1;
        this.importList.push(new ProductItem(data));
        this.react.renderTableImport();
    }
    //active record
    this.activeProductImport(pro_id);
};
ImportScript.getProductFromList = function (pro_id, instance) {
    for (var i in this.productList) {
        if (this.productList[i].pro_id == pro_id) {
            return instance ? this.productList[i].getInstances() : this.productList[i];
        }
    }
    return false;
};
ImportScript.getProductFromImport = function (pro_id, instance) {
    for (var i in this.importList) {
        if (this.importList[i].pro_id == pro_id) {
            return instance ? this.importList[i].getInstances() : this.importList[i];
        }
    }
    return false;
};
ImportScript.issetProduct = function (list, pro_id) {
    for (var i in list) {
        if (pro_id == list[i].pro_id)
            return true;
    }
    return false;
};
ImportScript.getIndexProduct = function (list, pro_id) {
    var index = -1;
    for (var i in list) {
        if (pro_id == list[i].pro_id)
            return i;
    }
    return index;
};
ImportScript.removeProduct = function (pro_id) {
    if (!this.issetProduct(this.importList, pro_id)) {
        return false;
    }
    var indexOf = this.getIndexProduct(this.importList, pro_id);

    if (indexOf > -1) {
        this.importList.splice(indexOf, 1);
    }
    //active product
    if (this.importList.length) {
        var lastIndexOf = this.importList.length - 1;
        if (lastIndexOf > indexOf) {
            this.activeProductImport(this.importList[indexOf].pro_id);
        } else if (lastIndexOf < indexOf) {
            this.activeProductImport(this.importList[lastIndexOf].pro_id)
        } else {
            this.activeProductImport(this.importList[lastIndexOf - 1].pro_id)
        }
    }

    this.react.renderTableImport();
};
ImportScript.setDebit = function () {
    ImportScript.billInfo.debit = $('#check-debit').is(':checked');
    ImportScript.domElement.debitInfo.toggleClass('active');
    if (ImportScript.billInfo.debit) {
        ImportScript.domElement.debitPrePay.removeAttr('disabled');
        ImportScript.domElement.debitSetDate.removeAttr('disabled');
    } else {
        ImportScript.domElement.debitPrePay.attr('disabled', true);
        ImportScript.domElement.debitSetDate.attr('disabled', true);
    }
};
ImportScript.billSubmit = function () {
    if (confirm('Bạn chắc chắn nhập xong các mặt hàng này?')) {
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                action: 'importProduct',
                products: ImportScript.importList,
                pay_type: ImportScript.billInfo.payType,
                start_date: ImportScript.billInfo.startDate,
                note: $('#bio_note').val(),
                supplier: $('#select-supplier').val(),
                store_id: $('#bio_store_id').val(),
                is_debit: ImportScript.billInfo.debit,
                money_debit: ImportScript.billInfo.debitMoney,
                date_debit: ImportScript.billInfo.debitDate
            },
            dataType: 'json',
            success: function (resp) {
                loadingProgress('hide');
                if (resp.success) {
                    window.parent.communicateParentWindow('closeImportProduct');
                } else {
                    alert(resp.error);
                }
            },
            beforeSend: function () {
                loadingProgress('show');
            }
        })
    }
};
$.contextMenu({
    selector: '.import-item',
    items: {
        delete: {
            name: '<i class="fa fa-trash"></i> Xóa mặt hàng này',
            callback: function () {
                var _this = $(this);
                ImportScript.removeProduct(_this.attr('data-id'));
            }
        }
    }
});

ImportScript.init();
$(function () {
    $('#mindow-listing-product').on('submit', 'form', function (e) {
        e.preventDefault();
        var _this = $(this);
        $.ajax({
            type: 'get',
            url: _this.attr('action'),
            data: _this.serialize() + '&action=searchAjax',
            success: function (resp) {
                $('#mindow-listing-product').html(resp);
            }
        });
    });

    ImportScript.react.renderTableImport();
});