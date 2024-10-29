jQuery(document).ready(function(){
	if(jQuery('#thema_categories').length){
		document.querySelector('#thema_expressions .plus_btn').onclick=addThemaExpression;
		var minus_btns = document.querySelectorAll('#thema_expressions .minus_btn');
		for(var i = 0; i < minus_btns.length; i++ ){
			minus_btns[i].onclick = removeThemaExpression;
		}
	}
});
function removeThemaExpression(){
	this.parentNode.parentNode.removeChild(this.parentNode);
}
function addThemaExpression(){
	var itm = document.getElementById('new_expression').firstElementChild;
	var selected_theme = document.querySelector("#new_expression .theme_selector");
	var selected_expression = document.querySelector("#new_expression .expression_selector");

	var cln = itm.cloneNode(true);
	cln.querySelector(".theme_selector").value = selected_theme.value;
	cln.querySelector(".expression_selector").value = selected_expression.value;
		
	var minus_img = document.createElement("img");
	minus_img.src = path_to + "/images/minus.png";
	minus_img.className  = "minus_btn";
	minus_img.onclick = removeThemaExpression;
		
	cln.replaceChild(minus_img, cln.querySelector(".plus_btn"));
	document.getElementById("thema_expressions").appendChild(cln);
		
	selected_theme.value = 0;
	selected_expression.value = "";
}