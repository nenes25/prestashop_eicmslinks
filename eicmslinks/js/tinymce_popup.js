$(document).ready(function () {

    /**
     * Affichage des onglets
     */
    $('.show-block-link').on('click', function () {
        $('.link-block').each(function () {
            $(this).css('display', 'none');
        });

        var elemToShow = $(this).attr('rel');
        $('#' + elemToShow).css('display', 'block');
    });

    /**
     * Récupération ajax des catégories
     */
    $.ajax({
        url: "../../../../" + admin_dir + "/" + ajax_page,
        method: "post",
        data: {
            action: 'CategoriesList',
            token: js_token
        },
        success: function (msg) {
            $("#category_content").html("").html(msg);
        }
    });

    /**
     * Récupération ajax des produits
     */
    _getProductsList();

    /**
     * Insertion d'une catégorie
     * Prestashop 1.6
     */
    $(document).on('click', '#categories-tree input', function () {
        addLink('{{category url=' + $(this).val() + '}}', $("#eicmslinks_textlink").val());
    });

    /**
     * Insertion d'une catégorie
     * Prestashop 1.5
     */
    $(document).on('click', '.category_label', function () {
        value = $(this).prev('input').val();
        addLink('{{category url=' + value + '}}', $("#eicmslinks_textlink").val());
    });

    /**
     * Insertion d'un produit
     */
    $(document).on('click', 'table.add_product_link_form tbody tr td.product-link', function () {
        id_product = $.trim($(this).parent().children('td.product_id').text());     
        addLink('{{product url=' + id_product + '}}', $("#eicmslinks_textlink").val());
    });

    /**
     * Ajout d'un lien d'ajout au panier
     */
    $(document).on('click', '.product-add-cart-link', function () {
        id_product = $.trim($(this).parent().children('td.product_id').text());
        addLink('{{cart url=' + id_product + '}}', $("#eicmslinks_textlink").val());
    });

    /**
     * Gestion de la recherche des produits
     * ( Effectuée en ajax )	 
     */
    $('#product_content').on('click', '#submitFilterButtonadd_product_link_form', function () {

        id = $('input[name="add_product_link_formFilter_id_product"]').val();
        reference = $('input[name="add_product_link_formFilter_reference"]').val();
        name = $('input[name="add_product_link_formFilter_name"]').val();

        _getProductsList(id, reference, name);
        return false;
    });

    /**
     * Réinitialisation de la recherche produit
     */
    $('#product_content').on('click', '.btn-warning,input[name="submitResetadd_product_link_form"]', function () {
        _getProductsList();
        return false;
    });

    /**
     * Fonction de récupération de la liste des produits 
     * @param int || vide id
     * @param string || vide reference
     * @param string || vide name
     */
    function _getProductsList(id, reference, name) {
        $.ajax({
            url: "../../../../" + admin_dir + "/" + ajax_page,
            method: "post",
            data: {
                action: 'ProductsList',
                token: js_token,
                add_product_link_formFilter_id_product: id,
                add_product_link_formFilter_reference: reference,
                add_product_link_formFilter_name: name
            },
            success: function (msg) {
                $("#product_content").html("").html(msg);
            }
        });
    }

});