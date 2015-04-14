$(document).ready(function(){
	/**
	 * Affichage des onglets
	 */
	$('.show-block-link').on('click',function(){
		$('.link-block').each(function(){
			$(this).css('display','none');
		});
		
		var elemToShow = $(this).attr('rel');
		$('#'+elemToShow).css('display','block');
	});	
	
	/**
	 * Récupération ajax des catégories
	 */
		$.ajax({
			url : "../../../../admin-dev/index.php?controller=Wysiwyg&module=eicmslinks&action=CategoriesList&ajax=1&token="+js_token,
			method : "post",
			success : function(msg){
				$("#category_content").html("").html(msg);
			}
		});
		
	/**
     * Insertion d'une catégorie
     */	
	 $('input[name="id-category-for-insert"]').on('click',function(){
		console.log($(this).val());
		var label = $(this).next('label').text();
		console.log(label);
	 });
	
});