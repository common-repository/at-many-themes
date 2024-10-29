jQuery(document).ready(function(){
	if(jQuery('#thema_categories').length){
		document.querySelector('#thema_pages .plus_btn').onclick=addThemaPage;
		var minus_btns = document.querySelectorAll('#thema_pages .minus_btn');
		for(var i = 0; i < minus_btns.length; i++ ){
			minus_btns[i].onclick = removeThemaPage;
		}
	}
});
function removeThemaPage(){
	this.parentNode.parentNode.removeChild(this.parentNode);
}
function addThemaPage(){
	var itm = document.getElementById('new_page').firstElementChild;
	var selected_theme = document.querySelector("#new_page .theme_selector");
	var selected_page = document.querySelector("#new_page .page_selector");

	var cln = itm.cloneNode(true);
	cln.querySelector(".theme_selector").value = selected_theme.value;
	cln.querySelector(".page_selector").value = selected_page.value;
		
	var minus_img = document.createElement("img");
	minus_img.src = path_to + "/images/minus.png";
	minus_img.className  = "minus_btn";
	minus_img.onclick = removeThemaPage;
		
	cln.replaceChild(minus_img, cln.querySelector(".plus_btn"));
	document.getElementById("thema_pages").appendChild(cln);
		
	selected_theme.value = 0;
	selected_page.value = 0;
}