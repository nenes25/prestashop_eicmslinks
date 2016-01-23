<?php
/**
 * Tests unitaires du module EiCmsLinks
 *
 * @author herve
 */
//@ToDo : Pour l'instant le path d'inclusion est déterminé par le chemin d'exécution
//Il faudra optimiser ce point, car les tests doivent être lancés de la manière suivante
// phpunit Tests/Unit/CustomerAutoGroupsTest.php à la racine du dossier du module

$exec_dir = str_replace('modules/eicmslinks','',trim(shell_exec('pwd')));
include_once $exec_dir.'config/config.inc.php';
include_once dirname(__FILE__).'/../../eicmslinks.php';

class EiCmsLinksTest extends PHPUnit_Framework_TestCase {
        
    //Pour tests locaux @ToDo : de manière dynamique
    protected $_baseDir;
    
    //Nom du module
    protected $_moduleName = 'eicmslinks';
    
    protected $_cmsList = false;
     
    /**
     * Surcharge pour gérer le contexte du chemin
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->_baseDir = str_replace('modules/'.$this->_moduleName,'',trim(shell_exec('pwd')));
        parent::__construct($name,$data, $dataName);
    }
    
    /**
     * Vérification que le module est installé (via la méthode prestashop)
     * @group eicmslinks_install
     */
    public function testModuleIsInstalled() {
        $this->assertTrue(Module::isInstalled($this->_moduleName));
    }
    
    /**
     * Vérifie que l'installation des overrides (fichiers) du module est OK
     * @group eicmslinks_install
     */
    public function testInstallOverrides() {

        $filesOverride = array(
            'controllers/front/CategoryController.php',
            'controllers/front/CmsController.php',
            'controllers/front/ProductController.php',
            'controllers/admin/templates/categories/helpers/form/form.tpl',
            'controllers/admin/templates/cms/helpers/form/form.tpl',
            'controllers/admin/templates/products/helpers/form/form.tpl',
        );

        foreach ($filesOverride as $file) {
            $this->assertFileExists($this->_baseDir .'override/'. $file);
        }
    }

    /**
     * Vérifie que l'onglet BO est bien installé
     * @group eicmslinks_install
     */
    public function testInstallTab(){
        $id_tab = Tab::getIdFromClassName('wysiwyg');
        $this->assertNotFalse($id_tab);
    }
    
    /**
     * Teste que les fichiers de l'éditeur de textes sont bien en place
     * @group eicmslinks_install
     */
    public function testInstallTinyMce(){
        
        $tinyMceFiles = array(
            'eicmslinks/ajax-tab.php',
            'eicmslinks/editor_plugin.js',
            'eicmslinks/editor_plugin_src.js',
            'eicmslinks/eicmslinks.php',
            'eicmslinks/plugin.min.js',
            'eicmslinks/img/eicmslinks.gif',
        );
        
        foreach ( $tinyMceFiles as $file ) {
            $this->assertFileExists($this->_baseDir.'js/tiny_mce/plugins/'.$file);
        }
    }
    
    /**
     * Test que le path est bien configuré dans l'admin
     * @group eicmslinks_install
     */
    public function testConfigAdminPath(){  
        
        $this->assertNotFalse(Configuration::get('eicmslinks_admin_path'));
        $this->assertNotEmpty(Configuration::get('eicmslinks_admin_path'));   
    }
    
    /**
     * Vérifie qu'au moins 2 pages Cms actives existe
     * @group eicmslinks_install
     */
    public function testCmsPageExists(){
        
        //Récuperation de la liste des pages cms
        $this->_cmsList = CMS::listCms();
        $this->assertNotFalse($this->_cmsList);
        $this->assertGreaterThan(2,sizeof($this->_cmsList));
    }
    
    /**
     * Vérifie qu'au moins 2 produits existe
     * @group eicmslinks_install
     */
    public function testProductExists() {

        //Pour les produits, les fonctions ont besoin du contexte, on le fait donc en sql
        $sql = 'SELECT p.`id_product`
				FROM `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
				WHERE pl.`id_lang` = ' . (int) Configuration::get('PS_LANG_DEFAULT') . '
				AND product_shop.`visibility` IN ("both", "catalog")
                                AND p.active = 1
				ORDER BY pl.`name`';
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $this->assertNotFalse($products);
        $this->assertGreaterThan(2, sizeof($products));
    }

    /**
     * Vérifie qu'au moins une catégorie existe
     * @group eicmslinks_install
     */
    public function testCategoryExists(){
  
        $categories = Category::getCategories();
        $this->assertNotFalse($categories);
        $this->assertGreaterThan(2,sizeof($categories));
    }
    
    
    /**
     * Test de la fonction de réécriture des liens
     * Vérifie que les données qui ressortent sont OK
     * @todo Factoriser la récupération des pages
     */
    public function testupdateCmsLinksDisplay(){
        
        $link = new Link();
        
        //Cms
        $this->_cmsList = CMS::listCms();
        $cmsLinkContent = EiCmsLinks::updateCmsLinksDisplay('{{cms url='.$this->_cmsList[1]['id_cms'].'}}');
        $rewriteCmsLink = $link->getCMSLink($this->_cmsList[1]['id_cms']);
        
        $this->assertEquals($cmsLinkContent,$rewriteCmsLink);
        
        //Categorie (@ToDO: Récupérer autrement )
        $categories = Category::getCategories();
        $categoryLinkContent = EiCmsLinks::updateCmsLinksDisplay('{{category url='.$categories[0][1]['infos']['id_category'].'}}');
        $rewriteCategoryLink = $link->getCategoryLink($categories[0][1]['infos']['id_category']);
        
        $this->assertEquals($categoryLinkContent,$rewriteCategoryLink);
        
        //Produit
        //Pour les produits, les fonctions ont besoin du contexte, on le fait donc en sql
        $sql = 'SELECT p.`id_product`, pl.`name`
				FROM `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
				WHERE pl.`id_lang` = ' . (int) Configuration::get('PS_LANG_DEFAULT') . '
				AND product_shop.`visibility` IN ("both", "catalog")
                                AND p.active = 1
				ORDER BY pl.`name`';
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $productLinkContent = EiCmsLinks::updateCmsLinksDisplay('{{product url='.$products[0]['id_product'].'}}');
        $productCategoryLink = $link->getProductLink($products[0]['id_product']);
        
        $this->assertEquals($productLinkContent,$productCategoryLink);
        
    }

    
    /**
     * @todo Tester encore les cas suivants
     *  - ajout d'un lien dans un objet ( cms / categorie / produit )
     *  - Vérification de la bonne sauvegarde de la données
     *  
     */
    
    
    }
