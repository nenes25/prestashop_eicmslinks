$(document).ready(function() {
    /**
     * Affichage des onglets
     */
    $('.show-block-link').on('click', function() {
        $('.link-block').each(function() {
            $(this).css('display', 'none');
        });

        var elemToShow = $(this).attr('rel');
        $('#' + elemToShow).css('display', 'block');
    });

    /**
     * Récupération ajax des catégories
     */
    $.ajax({
        url: "../../../../admin-dev/index.php?controller=Wysiwyg&module=eicmslinks&action=CategoriesList&ajax=1&token=" + js_token,
        method: "post",
        success: function(msg) {
            $("#category_content").html("").html(msg);
        }
    });
	
	/**
	 * Récupération ajax des produits
	 */
	_getProductsList();

    /**
     * Insertion d'une catégorie
     */
    $(document).on('click','#categories-tree input', function() {
        if ( $(this).next().next('label').text() )
            label = $(this).next().next('label').text();
        else
            label = $.trim($(this).parent().text());
        addLink('{{category url='+$(this).val()+'}}',label);
    });
	
	/**
	 * Insertion d'un produit
	 */
	 $(document).on('click','table.add_product_link_form tbody tr', function() {
		id_product = $.trim($(this).children('td.product_id').text());
		product_name = $.trim($(this).children('td.product_name').text());
		addLink('{{product url='+ id_product +'}}',product_name);
    });
	
	/**
	 * Gestion de la recherche des produits
     * ( Effectuée en ajax )	 
	 */
	 $(document).on('click','#submitFilterButtonadd_product_link_form',function(){
		
		id = $('input[name="add_product_link_formFilter_id_product"]').val();
		reference = $('input[name="add_product_link_formFilter_reference"]').val();
		name = $('input[name="add_product_link_formFilter_name"]').val();

		_getProductsList(id,reference,name);
		return false;
	 });
	 
	 /**
	  * Fonction de récupération de la liste des produits 
	  */
	 function _getProductsList(id,reference,name) {
		 $.ajax({
			url: "../../../../admin-dev/index.php?controller=Wysiwyg&module=eicmslinks&action=ProductsList&ajax=1&token=" + js_token,
			method: "post",
			data : {
				add_product_link_formFilter_id_product : id,
				add_product_link_formFilter_reference : reference,
				add_product_link_formFilter_name : name
			},
			success: function(msg) {
				$("#product_content").html("").html(msg);
			}	
		 });
	 }

});