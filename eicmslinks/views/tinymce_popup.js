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
	$.ajax({
        url: "../../../../admin-dev/index.php?controller=Wysiwyg&module=eicmslinks&action=ProductsList&ajax=1&token=" + js_token,
        method: "post",
        success: function(msg) {
            $("#product_content").html("").html(msg);
        }
    });

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

});