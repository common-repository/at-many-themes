jQuery(document).ready(function(){
	if(jQuery('#thema_categories').length){
		document.querySelector('#thema_categories .plus_btn').onclick=addThemaCategory;
		var minus_btns = document.querySelectorAll('#thema_categories .minus_btn');
		for(var i = 0; i < minus_btns.length; i++ ){
			minus_btns[i].onclick = removeThemaCategory;
		}
	}
});
function removeThemaCategory(){
	this.parentNode.parentNode.removeChild(this.parentNode);
	
	updateCheckboxName();
}
function addThemaCategory(){
	var itm = document.getElementById('new_category').firstElementChild;
	var selected_theme = document.querySelector("#new_category .theme_selector");
	var selected_category = document.querySelector("#new_category .category_selector");
	var category_checkbox = document.querySelector("#new_category .category_checkbox");

	var cln = itm.cloneNode(true);
	cln.querySelector(".theme_selector").value = selected_theme.value;
	cln.querySelector(".category_selector").value = selected_category.value;
		
	var minus_img = document.createElement("img");
	minus_img.src = path_to + "/images/minus.png";
	minus_img.className  = "minus_btn";
	minus_img.onclick = removeThemaCategory;
		
	cln.replaceChild(minus_img, cln.querySelector(".plus_btn"));
	document.getElementById("thema_categories").appendChild(cln);
		
	selected_theme.value = 0;
	selected_category.value = 0;
	category_checkbox.value.checked = false;
	
	updateCheckboxName();
}
function updateCheckboxName(){
	var _numbr = 0;
	jQuery(".category_checkbox").each(function(){
		jQuery(this).attr("name", "for_all[" + _numbr + "]");
		_numbr++;
	});
}