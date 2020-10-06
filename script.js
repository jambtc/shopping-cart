var purchased=new Array();
var totalprice=0;

$(document).ready(function(){
	$('.product').simpletip({
		offset:[40,0],
		content:'<img src="img/ajax_load.gif" alt="loading" style="margin:10px;" />',
		onShow: function(){

			var param = this.getParent().find('img').attr('src');

			if($.browser.msie && $.browser.version=='6.0'){
				param = this.getParent().find('img').attr('style').match(/src=\"([^\"]+)\"/);
				param = param[1];
			}
			// after the tooltip is shown, load the tips.php file and pass the image name as a parameter
			this.load('ajax/tips.php',{img:param});
		}
	});

	// enable all product images to be dragged
	$(".product img").draggable({
		containment: 'document',
		opacity: 0.6,
		revert: 'invalid',
		helper: 'clone',
		zIndex: 100
	});

	// convert the shopping cart to a droppable
	$("div.content.drop-here").droppable({
		drop:
			function(e, ui){
				var param = $(ui.draggable).attr('src');

				if($.browser.msie && $.browser.version=='6.0'){
					param = $(ui.draggable).attr('style').match(/src=\"([^\"]+)\"/);
					param = param[1];
				}

				addlist(param);
			}
	});
});

// the addlist function ads a product to the shopping cart
function addlist(param){
	$.ajax({				// sending an ajax request to addtocart.php
		type: "POST",
		url: "ajax/addtocart.php",
		data: 'img='+encodeURIComponent(param),	// the product image as a parameter
		dataType: 'json',
		beforeSend: function(x){
			$('#ajax-loader').css('visibility','visible');
		},
		success: function(msg){
			$('#ajax-loader').css('visibility','hidden');
			if(parseInt(msg.status)!=1){
				return false;		// if there has been an error, return false
			}else{
				var check=false;
				var cnt = false;

				for(var i=0; i<purchased.length;i++){
					// find if we have already bought this prduct
					if(purchased[i].id==msg.id){
						check=true;
						cnt=purchased[i].cnt;

						break;
					}
				}

				// if we haven't bought it yet, or we have removed it from the purchases,
				// we insert it in the shopping cart
				if(!cnt)
					$('#item-list').append(msg.txt);

				// if we haven't bought it yet, insert it in the purchased array
				if(!check){
					purchased.push({id:msg.id,cnt:1,price:msg.price});
				}else{
					// else if we've bought it
					if(cnt>=3) return false; // 3 products of type max

					purchased[i].cnt++;
					$('#'+msg.id+'_cnt').val(purchased[i].cnt);
				}

				totalprice+=msg.price;
				update_total();
			}
			$('.tooltip').hide();
		}
	});
}

// a function that finds the position at which the product is inserted in the array
// it returns the position
function findpos(id){
	for(var i=0; i<purchased.length;i++){
		if(purchased[i].id==id)
			return i;
	}

	return false;
}

// remove a product from the shopping cart
function remove(id){
	var i=findpos(id);

	totalprice-=purchased[i].price*purchased[i].cnt;
	purchased[i].cnt = 0;

	$('#table_'+id).remove();
	update_total();
}

// change the number of products via the select area
function change(id){
	var i=findpos(id);

	totalprice+=(parseInt($('#'+id+'_cnt').val())-purchased[i].cnt)*purchased[i].price;

	purchased[i].cnt=parseInt($('#'+id+'_cnt').val());
	update_total();
}

// function that updates the total price div on the page
function update_total(){
	if(totalprice){
		// if we've bought somehitng, show the total price div and the purchase button
		$('#total').html('total: $'+totalprice);
		$('a.button').css('display','block');
	}else{
		$('#total').html('');
		$('a.button').hide();
	}
}
