function headClick() {
	$('.h .more').on("click", function() {
		$(this).find('.nav').toggle()
	});
}

var i = 1;

function addTxt() {
	i++;
	$('.module_2 .nub').val(i);
}

function jianTxt() {
	if($('.module_2 .nub').val() <= 1) {
		alert('不能再减了');
	} else {
		i--;
		$('.module_2 .nub').val(i);
	}
}

function chle() {
	var i = 0;
	$('.module_9 ul li').attr("data-type", 0);
	$('.module_9 ul li').click(function() {
		if($(this).attr("data-type") == 0) {
			$(this).addClass('on');
			$(this).attr("data-type", 1);
		} else if($(this).attr("data-type") == 1) {
			$(this).removeClass('on');
			$(this).attr("data-type", 0);
		}
	})
}

$(function() {
	$('.module_12 ul').each(function() {
		$(this).children("li").last().css({
			"border-bottom": "0px none"
		})
	});
})
 