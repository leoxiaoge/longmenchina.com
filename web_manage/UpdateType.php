<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="css/UpdateGoods.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
<script src="js/UpdateGoods.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function () {
		String.prototype.getQuery = function (name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
			var r = this.substr(this.indexOf("\?") + 1).match(reg);
			if (r != null) return unescape(r[2]); return null;
		}
		var id = $("#HiddenField2").val(); // Url.getQuery("goodId");

		SaveGoodsType(
		TemplateID = 1, //模板ID。来自模板表(Template)
		InsertOrUpdate = 1, //0是添加，1是修改，默认为0，添加商品或者是修改商品。如果是添加商品需要将GoodsID手动改为0!如果是修改则传入正常的商品ID
		GoodsID = id, //商品ID。来自商品表(Prodect)

		TemplateOpen = 0, //是否读模版，0为读模版，需要传入正确的模版ID,1为自定义，需要给规格传数组。
		//规格1的变量如果TemplateOpen为1则必须输入规格数组
		//列:
		ColorName = ["白色", "红色"],
		//规格2的变量如果TemplateOpen为1则必须输入规格数组
		//列:
		SizeName = ["XL", "XXL"]
		)
	})
</script>


</head>
<body>
<form method="post" action="" id="selectform">
    <div id="content">
        <label style="font-size: 13px;">
            添加产品名称：</label>
        <ul id="u1">
        </ul>
        <div style="clear: both;">
        </div>
        <label style="font-size: 13px;">
            价格类型：</label>
        <ul id="u2">
        </ul>
        <div style="clear: both;">
        </div>
    </div>
    <div id="dt" style="margin-bottom: 50px;">
        <table border="0" cellspacing="1" bgcolor="#D7D7D7" id="tb1">
            <thead id="th1">
                <tr>
                    <td>
                        产品名称
                    </td>
                    <td>
                        价格类型
                    </td>
                    <td style="width: 200px;">
                        价格<label style="color: Red">*</label>
                    </td>
                    <td style="width: 200px;">
                        数量<label style="color: Red">*</label>
                    </td>
                </tr>
            </thead>
            <tbody id="tbd1">
            </tbody>
        </table>
    </div>
	<input type="hidden" name="HiddenField1" id="HiddenField1" />
    <input type="submit" name="b1" value="提交" id="b1" />
    </form>
</body>
</html>
