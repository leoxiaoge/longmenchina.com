//添加商品或者是修改商品，0是添加，1是修改，默认为0。如果是添加商品需要将GoodsID手动改为0!如果是修改则传入正常的商品ID
var InsertOrUpdate = 0;
//商品ID全局变量名称
var GoodsID = 0;
//模版ID全局变量名称
var TemplateID = 0;
//是读模版还是自己写规格的开关变量名称，0为读模版，需要模版ID，1为读字符串需要给字符串变量赋值
var TemplateOpen = 0;
var ColorName = "";
var SizeName = "";

function SaveGoodsType() {
    if (TemplateOpen == 0) {
        //遍历颜色添加到颜色复选框中
        $.post("/web_manage/LoginTemplate.php", { "TemplateID": TemplateID }, function (data) {
            var Template = eval(data);
            ColorName = Template["ColorName"];
            SizeName = Template["SizeName"];
            for (var i = 1; i <= ColorName.length; i++) {
                var li = $("<li></li>").appendTo("#u1");
                var checkbox = $("<input class='c1' type='checkbox' />").appendTo(li);
                checkbox.attr("id", i);
                var label = $("<label></label>").appendTo(li);
                label.text(ColorName[i - 1]);
                label.click(function () {
                    $(this).prev().prop("checked", true);
                    $(this).next().show();
                    $(this).hide();
                    CreateColor();
                })
                //添加文本框，修改值的时候修改table的值
                var text1 = $("<input type='text' />").appendTo(li);
                text1.hide();
                text1.val(label.text());
                text1.blur(function () {
                    var j = $(this).prev().prev().attr("id");
                    $("td").filter("." + j).text($(this).val());
                })
            }
            //遍历尺码添加到尺码复选框中
            for (var i = 1; i <= SizeName.length; i++) {
                var li = $("<li></li>").appendTo("#u2");
                var checkbox = $("<input class='c2' type='checkbox' />").appendTo(li);
                checkbox.attr("id", i + 100);
                var label = $("<label></label>").appendTo(li);
                label.text(SizeName[i - 1]);
                label.click(function () {
                    $(this).prev().prop("checked", true);
                    $(this).next().show();
                    $(this).hide();
                    CreateSize();
                })
                var text1 = $("<input type='text' />").appendTo(li);
                text1.hide();
                text1.val(label.text());
                text1.blur(function () {
                    var j = $(this).prev().prev().attr("id");
                    $("td").filter("." + j).text($(this).val());
                })
            }

            //单击复选框创建表格。
            $("#u1 input[type=checkbox]").click(function () {
                $(this).each(function () {
                    if ($(this).prop("checked")) {
                        $(this).next().hide();
                        $(this).next().next().show();
                        CreateColor();
                    }
                    else {
                        $(this).next().show();
                        $(this).next().next().hide();
                        DeleteColor();
                    }
                })
            })

            //单击复选框创建表格。
            $("#u2 input[type=checkbox]").click(function () {
                $(this).each(function () {
                    if ($(this).prop("checked")) {
                        $(this).next().hide();
                        $(this).next().next().show();
                        CreateSize();
                    }
                    else {
                        $(this).next().show();
                        $(this).next().next().hide();
                        DeleteSize();
                    }
                })
            })
            //读取商品的规格,当为修改时
            if (InsertOrUpdate == 1) {
                LoginGoodsType();
            }
        }, "JSON");
    }
    else {

        for (var i = 1; i <= ColorName.length; i++) {
            var li = $("<li></li>").appendTo("#u1");
            var checkbox = $("<input class='c1' type='checkbox' />").appendTo(li);
            checkbox.attr("id", i);
            var label = $("<label></label>").appendTo(li);
            label.text(ColorName[i - 1]);
            label.click(function () {
                $(this).prev().prop("checked", true);
                $(this).next().show();
                $(this).hide();
                CreateColor();
            })
            //添加文本框，修改值的时候修改table的值
            var text1 = $("<input type='text' />").appendTo(li);
            text1.hide();
            text1.val(label.text());
            text1.blur(function () {
                var j = $(this).prev().prev().attr("id");
                $("td").filter("." + j).text($(this).val());
            })
        }

        //遍历尺码添加到尺码复选框中
        for (var i = 1; i <= SizeName.length; i++) {
            var li = $("<li></li>").appendTo("#u2");
            var checkbox = $("<input class='c2' type='checkbox' />").appendTo(li);
            checkbox.attr("id", i + 100);
            var label = $("<label></label>").appendTo(li);
            label.text(SizeName[i - 1]);
            label.click(function () {
                $(this).prev().prop("checked", true);
                $(this).next().show();
                $(this).hide();
                CreateSize();
            })
            var text1 = $("<input type='text' />").appendTo(li);
            text1.hide();
            text1.val(label.text());
            text1.blur(function () {
                var j = $(this).prev().prev().attr("id");
                $("td").filter("." + j).text($(this).val());
            })
        }

        //单击复选框创建表格。
        $("#u1 input[type=checkbox]").click(function () {
            $(this).each(function () {
                if ($(this).prop("checked")) {
                    $(this).next().hide();
                    $(this).next().next().show();
                    CreateColor();
                }
                else {
                    $(this).next().show();
                    $(this).next().next().hide();
                    DeleteColor();
                }
            })
        })

        //单击复选框创建表格。
        $("#u2 input[type=checkbox]").click(function () {
            $(this).each(function () {
                if ($(this).prop("checked")) {
                    $(this).next().hide();
                    $(this).next().next().show();
                    CreateSize();
                }
                else {
                    $(this).next().show();
                    $(this).next().next().hide();
                    DeleteSize();
                }
            })
        })
        //读取商品的规格
        LoginGoodsType();
    }




    //提交按钮事件
    $("#b1").click(function () {
        ck = true;
        //判断表格是否有空
        if ($("#tbd1").find("input").filter(":visible").length == 0) {
            ck = false;
        }
        else {
            $("#tbd1").find("input").filter(":visible").each(function () {
                if ($(this).val() == "") {
                    ck = false;
                }
                if ($(this).length == 0) {
                    ck = false;
                }
            })
        }

        //删除规格后重新添加规格
        if (ck == true) {
            var str = "";

            $.post("/web_manage/DeleteGoodsType.php", { "GoodsID": GoodsID }, function () {
            });

            //↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
            var trs = $("#tbd1").find("tr").filter(":visible");
            trs.each(function () {
                var tds = $(this).find("td");
                var attr1Name = tds.eq(0).text();
                var attr1ID = tds.eq(0).attr("class");
                var attr2Name = tds.eq(1).text();
                var attr2ID = tds.eq(1).attr("class");
                var GoodsPrice = tds.eq(2).find("input").val();
                var GoodsCount = tds.eq(3).find("input").val();
                str = str + GoodsID + "|" + attr1ID + "|" + attr1Name + "|" + attr2ID + "|" + attr2Name + "|" + GoodsPrice + "|" + GoodsCount + ",";
            });
            //↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
			 
            if (InsertOrUpdate == 1) {
                alert("修改成功");
            }
            else {
                alert("添加成功");
            }
            $("#HiddenField1").val(str);
            self.parent.document.getElementById("txtPSupplierName").value = $("#HiddenField1").val();
			 
			//self.parent.layer.close();
			 
        }
        else {
            alert("请填写数量价格或名称");
            return false;
        }

    })

    $(".l1").hide();



}


function LoginGoodsType() {
    //从数据库读出Attr1和Attr2的ID和NAME将复选框的钩打上把值修改下
    $.post("/web_manage/GetAttr1.php", { "GoodsID": GoodsID }, function (data) {
        var Attrlist = eval(data);
        for (var i = 0; i < Attrlist.length; i++) {
            var colorid = Attrlist[i].AttrID;
            var colorname = Attrlist[i].AttrName;
            $("#" + colorid).prop("checked", true);
            $("#" + colorid).next().next().val(colorname).show();
            $("#" + colorid).next().hide();
        }
        $.post("/web_manage/GetAttr2.php", { "GoodsID": GoodsID }, function (data) {
            var Attrlist = eval(data);
            for (var i = 0; i < Attrlist.length; i++) {
                var sizeid = Attrlist[i].AttrID;
                var sizename = Attrlist[i].AttrName;
                $("#" + sizeid).prop("checked", true);
                $("#" + sizeid).next().next().val(sizename).show();
                $("#" + sizeid).next().hide();
            }
            //获得列合并数
            rowspan = $("#u2 input:checked").length;
            //根据复选框的状态生成table
            $("#u1 input:checked").each(function () {
                var colorid = $(this).attr("id");
                $("#u2 input:checked").each(function () {
                    //判断关联tr是否存在，不存在则创建
                    var sizeid = $(this).attr("id");
                    var id = colorid + sizeid;
                    var tr = $("#" + id);
                    if (!(tr.length > 0)) {
                        //判断颜色集合tr是否存在，如果存在就在颜色集合tr的末尾增加tr如果不存在则创建颜色td添加在表格末尾
                        tr = $("<tr></tr>");
                        tr.attr("id", id);
                        tr.attr("class", colorid);
                        var td = $("<td></td>").appendTo(tr);
                        td.attr("class", colorid);
                        td.text($("#" + colorid).next().next().val());
                        var td1 = $("<td></td>").appendTo(tr);
                        td1.attr("class", sizeid);
                        td1.text($("#" + sizeid).next().next().val());
                        var td2 = $("<td></td>").appendTo(tr);
                        var td3 = $("<td></td>").appendTo(tr);
                        var label1 = $("<label>请输入数值</label>").css("color", "Red");
                        var label2 = $("<label>请输入整数</label>").css("color", "Red");
                        $("<input type='text' />").css("width", "80px").appendTo(td2).blur(function () {
                            //验证价格是否规范，如果不规范，则清空价格，否则保存价格
                            reg = /^(\d+[.]{0,1}\d+|\d+)$/;
                            if (!reg.test($(this).val())) {
                                $(this).val("");
                                label1.appendTo(td2);
                            }
                            else {
                                label1.remove();
                            }
                        });
                        $("<input type='text' />").css("width", "80px").appendTo(td3).blur(function () {
                            //验证数据是否有非数字，如果有，则清空数量，无则保存数量
                            reg = /^\d+$/;
                            if (!reg.test($(this).val())) {
                                $(this).val("");
                                label2.appendTo(td3);
                            }
                            else {
                                label2.remove();
                            }
                        });

                        //判断颜色集合是否存在，存在添加至颜色集合末尾，不存在添加至表格末尾
                        if ($("tr").filter("." + colorid).length > 0) {
                            tr.insertAfter($("tr").filter("." + colorid).filter(":last"));
                        }
                        else {
                            tr.appendTo("#dt #tbd1");
                        }
                        //判断tr是否是颜色集合第一个，是就显示td不是就隐藏td
                        if ($("td").filter(":visible").filter("." + colorid).length > 1) {
                            td.hide();
                        }
                        else {
                            td.show();
                        }
                    }
                });
            });
            $("#tbd1 tr").each(function () {
                $(this).find("td").eq(0).attr("rowspan", rowspan);
            });
            //读取数量和价格，根据属性找到tr并修改它的值
            $.post("Handler/GetGoodsType.ashx", { "GoodsID": GoodsID }, function (data) {

                var trs = eval(data);

                

                for (var i = 0; i < trs.length; i++) {
                    var tr = trs[i];
                    var id = tr.Attr1ID + tr.Attr2ID;
                    var Count = tr.GoodsCount;
                    var Price = tr.GoodsPrice;
                    var tds = $("#" + id).find("td");
                    tds.eq(2).find("input").val(Price);
                    tds.eq(3).find("input").val(Count);

                    //alert(Price);
                }
            }, "JSON");
        }, "JSON")
    }, "JSON")

}

var rowspan = 0;
function CreateColor() {
    $("#dt").find("#tbd1").find("tr").hide();
    //循环获取每个tr
    $("#u1 input:checked").each(function () {
        var colorid = $(this).attr("id");
        $("#u2 input:checked").each(function () {
            //判断关联tr是否存在，不存在则创建
            var sizeid = $(this).attr("id");
            var id = colorid + sizeid;
            var tr = $("#" + id);
            if (!(tr.length > 0)) {
                //判断颜色集合tr是否存在，如果存在就在颜色集合tr的末尾增加tr如果不存在则创建颜色td添加在表格末尾
                tr = $("<tr></tr>");
                tr.attr("id", id);
                tr.attr("class", colorid);
                var td = $("<td></td>").appendTo(tr);
                td.attr("class", colorid);
                td.text($("#" + colorid).next().next().val());
                var td1 = $("<td></td>").appendTo(tr);
                td1.attr("class", sizeid);
                td1.text($("#" + sizeid).next().next().val());
                var td2 = $("<td></td>").appendTo(tr);
                var td3 = $("<td></td>").appendTo(tr);
                var label1 = $("<label>请输入数值</label>").css("color", "Red");
                var label2 = $("<label>请输入整数</label>").css("color", "Red");
                $("<input type='text' />").css("width", "80px").appendTo(td2).blur(function () {
                    //验证价格是否规范，如果不规范，则清空价格，否则保存价格
                    reg = /^(\d+[.]{0,1}\d+|\d+)$/;
                    if (!reg.test($(this).val())) {
                        $(this).val("");
                        label1.appendTo(td2);
                    }
                    else {
                        label1.remove();
                    }
                });
                $("<input type='text' />").css("width", "80px").appendTo(td3).blur(function () {
                    //验证数据是否有非数字，如果有，则清空数量，无则保存数量
                    reg = /^\d+$/;
                    if (!reg.test($(this).val())) {
                        $(this).val("");
                        label2.appendTo(td3);
                    }
                    else {
                        label2.remove();
                    }
                });

                //判断颜色集合是否存在，存在添加至颜色集合末尾，不存在添加至表格末尾
                if ($("tr").filter("." + colorid).length > 0) {
                    tr.insertAfter($("tr").filter("." + colorid).filter(":last"));
                }
                else {
                    tr.appendTo("#dt #tbd1");
                }
                //判断tr是否是颜色集合第一个，是就显示td不是就隐藏td
                if ($("td").filter(":visible").filter("." + colorid).length > 1) {
                    td.hide();
                }
                else {
                    td.show();
                }

            }
            //存在则显示
            else {
                tr.show();
                //如果当前tr是颜色集合中显示的第一个tr就显示，如果不是就隐藏颜色td
                if (tr.html() == $("tr").filter("." + colorid).filter(":visible").eq(0).html()) {
                    $("tr").filter("." + colorid).each(function () {
                        $(this).find("td").eq(0).hide();
                    })
                    tr.find("td").eq(0).show();
                }
                else {
                    tr.find("td").eq(0).hide();
                }
            }
        })
    });
    $("#tbd1 tr").each(function () {
        $(this).find("td").eq(0).attr("rowspan", rowspan);
    })
}

function DeleteColor() {
    $("#dt").find("#tbd1").find("tr").hide();
    //循环遍历每个关联tr
    $("#u1 input:checked").each(function () {
        var colorid = $(this).attr("id");
        $("#u2 input:checked").each(function () {
            var sizeid = $(this).attr("id");
            var id = colorid + sizeid;
            var tr = $("#" + id);
            tr.show();
            //如果当前tr是颜色集合中显示的第一个tr就显示，如果不是就隐藏颜色td
            if (tr.html() == $("tr").filter("." + colorid).filter(":visible").eq(0).html()) {
                $("tr").filter("." + colorid).each(function () {
                    $(this).find("td").eq(0).hide();
                })
                tr.find("td").eq(0).show();
            }
            else {
                tr.find("td").eq(0).hide();
            }
        })
    });
    $("#tbd1 tr").each(function () {
        $(this).find("td").eq(0).attr("rowspan", rowspan);
    })
}

function CreateSize() {
    rowspan = rowspan + 1;
    $("#dt").find("#tbd1").find("tr").hide();
    //遍历关联tr
    $("#u1 input:checked").each(function () {
        var colorid = $(this).attr("id")
        $("#u2 input:checked").each(function () {
            var sizeid = $(this).attr("id");
            var id = colorid + sizeid;
            var tr = $("#" + id);
            //如果tr不存在则创建
            if (!(tr.length > 0)) {
                tr = $("<tr></tr>");
                tr.attr("id", id);
                tr.attr("class", colorid);
                var td = $("<td></td>").appendTo(tr);
                td.attr("class", colorid);
                td.text($("#" + colorid).next().next().val());
                var td1 = $("<td></td>").appendTo(tr);
                td1.attr("class", sizeid);
                td1.text($("#" + sizeid).next().next().val());
                var td2 = $("<td></td>").appendTo(tr);
                var td3 = $("<td></td>").appendTo(tr);
                var label1 = $("<label>请输入数值</label>").css("color", "Red");
                var label2 = $("<label>请输入整数</label>").css("color", "Red");
                $("<input type='text' />").css("width", "80px").appendTo(td2).blur(function () {
                    //验证价格是否规范，如果不规范，则清空价格，否则保存价格
                    reg = /^(\d+[.]{0,1}\d+|\d+)$/;
                    if (!reg.test($(this).val())) {
                        $(this).val("");
                        label1.appendTo(td2);
                    }
                    else {
                        label1.remove();
                    }
                });
                $("<input type='text' />").css("width", "80px").appendTo(td3).blur(function () {
                    //验证数据是否有非数字，如果有，则清空数量，无则保存数量
                    reg = /^\d+$/;
                    if (!reg.test($(this).val())) {
                        $(this).val("");
                        label2.appendTo(td3);
                    }
                    else {
                        label2.remove();
                    }
                });
                //判断颜色集合是否存在，存在添加至颜色集合末尾，不存在添加至表格末尾
                if ($("tr").filter("." + colorid).length > 0) {
                    tr.insertAfter($("tr").filter("." + colorid).filter(":last"));
                }
                else {
                    tr.appendTo("#dt #tbd1");
                }

                if ($("td").filter(":visible").filter("." + colorid).length > 1) {
                    td.hide();
                }
                else {
                    td.show();
                }
            }
            //存在则显示
            else {
                tr.show();
                //如果当前tr是颜色集合中显示的第一个tr就显示，如果不是就隐藏颜色td
                if (tr.html() == $("tr").filter("." + colorid).filter(":visible").eq(0).html()) {
                    $("tr").filter("." + colorid).each(function () {
                        $(this).find("td").eq(0).hide();
                    })
                    tr.find("td").eq(0).show();
                }
                else {
                    tr.find("td").eq(0).hide();
                }
            }
        })

    })
    $("#tbd1 tr").each(function () {
        $(this).find("td").eq(0).attr("rowspan", rowspan);
    })
}
function DeleteSize() {
    rowspan = rowspan - 1;
    //循环遍历每个关联tr
    $("#dt #tbd1 tr").hide();
    $("#u1 input:checked").each(function () {
        var colorid = $(this).attr("id");
        $("#u2 input:checked").each(function () {
            var sizeid = $(this).attr("id");
            var id = colorid + sizeid;
            var tr = $("#" + id);
            tr.show();
            //如果当前tr是颜色集合中显示的第一个tr就显示，如果不是就隐藏颜色td
            if (tr.html() == $("tr").filter("." + colorid).filter(":visible").eq(0).html()) {
                $("tr").filter("." + colorid).each(function () {
                    $(this).find("td").eq(0).hide();
                })
                tr.find("td").eq(0).show();

            }
            else {
                tr.find("td").eq(0).hide();
            }
        })

    })


    //获取所有合并td合并数减1
    $("#tbd1 tr").each(function () {
        $(this).find("td").eq(0).attr("rowspan", rowspan);
    })
}