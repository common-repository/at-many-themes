jQuery(document).ready(function(){
	if(jQuery('#thema_categories').length){
		document.querySelector('#thema_posts .plus_btn').onclick=addThemaPost;
		var minus_btns = document.querySelectorAll('#thema_posts .minus_btn');
		for(var i = 0; i < minus_btns.length; i++ ){
			minus_btns[i].onclick = removeThemaPost;
		}
	}
});

function removeThemaPost(){
	this.parentNode.parentNode.removeChild(this.parentNode);
}
function addThemaPost(){
	var itm = document.getElementById('new_post').firstElementChild;
	var selected_theme = document.querySelector("#new_post .theme_selector");
	var selected_post = document.querySelector("#new_post .post_selector");

	var cln = itm.cloneNode(true);
	cln.querySelector(".theme_selector").value = selected_theme.value;
	cln.querySelector(".post_selector").value = selected_post.value;
		
	var minus_img = document.createElement("img");
	minus_img.src = path_to + "/images/minus.png";
	minus_img.className  = "minus_btn";
	minus_img.onclick = removeThemaPost;
		
	cln.replaceChild(minus_img, cln.querySelector(".plus_btn"));
	document.getElementById("thema_posts").appendChild(cln);
		
	selected_theme.value = 0;
	selected_post.value = 0;
}